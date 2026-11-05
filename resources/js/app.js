

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.querySelectorAll('[data-quantity-control]').forEach((control) => {
    const buttons = control.querySelectorAll('[data-quantity-action]');
    const amountInput = control.querySelector('[data-quantity-amount]');
    const quantityValue = control.querySelector('[data-quantity-value]');
    const message = control.parentElement.querySelector('[data-quantity-message]');

    buttons.forEach((button) => {
        button.addEventListener('click', async () => {
            const amount = Number(amountInput.value);
            if (!Number.isInteger(amount) || amount < 1) {
                message.textContent = 'Ievadiet pozitīvu veselu skaitli.';
                return;
            }

            buttons.forEach((item) => {
                item.disabled = true;
            });
            message.textContent = '';

            try {
                const response = await fetch(control.dataset.url, {
                    method: 'PATCH',
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        direction: button.dataset.quantityAction,
                        amount,
                    }),
                });
                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || 'Neizdevās atjaunināt produkta daudzumu.');
                }

                quantityValue.textContent = result.quantity;
                message.textContent = result.message;
            } catch (error) {
                message.textContent = error instanceof Error
                    ? error.message
                    : 'Neizdevās atjaunināt produkta daudzumu.';
            } finally {
                buttons.forEach((item) => {
                    item.disabled = false;
                });
            }
        });
    });
});

const tagEditor = document.querySelector('[data-tag-editor]');

if (tagEditor) {
    const input = tagEditor.querySelector('[data-tag-input]');
    const suggestions = tagEditor.querySelector('[data-tag-suggestions]');
    const chips = tagEditor.querySelector('[data-tag-chips]');
    const feedback = tagEditor.querySelector('[data-tag-feedback]');
    let searchTimeout;
    let searchController;

    const clearSuggestions = () => {
        suggestions.replaceChildren();
        suggestions.classList.add('hidden');
    };

    const addTag = (name) => {
        const tagName = name.trim();
        if (!tagName) {
            return;
        }

        const existingTags = [...chips.querySelectorAll('input[name="tags[]"]')]
            .map((tag) => tag.value.toLocaleLowerCase());
        if (existingTags.includes(tagName.toLocaleLowerCase())) {
            feedback.textContent = 'Šī birka jau ir pievienota.';
            input.value = '';
            clearSuggestions();
            return;
        }

        const chip = document.createElement('span');
        chip.className = 'inline-flex items-center gap-2 rounded-full bg-indigo-500/20 px-3 py-1 text-sm text-indigo-100';
        chip.dataset.tagChip = '';

        const label = document.createElement('span');
        label.textContent = tagName;

        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'tags[]';
        hiddenInput.value = tagName;

        const removeButton = document.createElement('button');
        removeButton.className = 'font-bold hover:text-white';
        removeButton.type = 'button';
        removeButton.dataset.removeTag = '';
        removeButton.setAttribute('aria-label', `Noņemt birku ${tagName}`);
        removeButton.textContent = '×';

        chip.append(label, hiddenInput, removeButton);
        chips.append(chip);
        input.value = '';
        feedback.textContent = '';
        clearSuggestions();
        input.focus();
    };

    const showSuggestions = (tags) => {
        suggestions.replaceChildren();

        tags.forEach((tag) => {
            const option = document.createElement('li');
            option.setAttribute('role', 'option');

            const button = document.createElement('button');
            button.className = 'block w-full px-3 py-2 text-left text-sm hover:bg-gray-700';
            button.type = 'button';
            button.textContent = tag.name;
            button.addEventListener('click', () => addTag(tag.name));

            option.append(button);
            suggestions.append(option);
        });

        suggestions.classList.toggle('hidden', tags.length === 0);
    };

    input.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchController?.abort();

        const query = input.value.trim();
        if (!query) {
            clearSuggestions();
            feedback.textContent = '';
            return;
        }

        searchTimeout = setTimeout(async () => {
            searchController = new AbortController();

            try {
                const url = new URL(tagEditor.dataset.searchUrl, window.location.origin);
                url.searchParams.set('query', query);
                const response = await fetch(url, {
                    headers: { Accept: 'application/json' },
                    signal: searchController.signal,
                });

                if (!response.ok) {
                    throw new Error('Birkas neizdevās ielādēt.');
                }

                const result = await response.json();
                showSuggestions(result.tags);
                feedback.textContent = result.tags.length === 0
                    ? 'Esošu birku nav; nospiežot Enter, tiks pievienota jauna.'
                    : 'Izvēlieties birku vai nospiediet Enter, lai pievienotu pirmo.';
            } catch (error) {
                if (error.name !== 'AbortError') {
                    clearSuggestions();
                    feedback.textContent = error instanceof Error ? error.message : 'Birkas neizdevās ielādēt.';
                }
            }
        }, 200);
    });

    input.addEventListener('keydown', (event) => {
        if (event.key === 'Enter') {
            event.preventDefault();
            const firstSuggestion = suggestions.querySelector('button');
            addTag(firstSuggestion?.textContent ?? input.value);
        } else if (event.key === 'Escape') {
            clearSuggestions();
        }
    });

    chips.addEventListener('click', (event) => {
        const removeButton = event.target.closest('[data-remove-tag]');
        if (removeButton) {
            removeButton.closest('[data-tag-chip]').remove();
        }
    });
}
