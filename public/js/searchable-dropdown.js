document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-searchable-dropdown]').forEach(function (wrapper) {
        var searchInput = wrapper.querySelector('[data-search-input]');
        var valueInput = wrapper.querySelector('[data-value-input]');
        var resultsBox = wrapper.querySelector('[data-search-results]');
        var url = wrapper.dataset.url || '';
        var options = JSON.parse(wrapper.dataset.options || '[]');
        var parentSelector = wrapper.dataset.parent || '';
        var parentParam = wrapper.dataset.parentParam || '';
        var parentRequired = wrapper.dataset.parentRequired === 'true';
        var fillTargetSelector = wrapper.dataset.fillTarget || '';
        var fillField = wrapper.dataset.fillField || '';
        var selectedText = searchInput.value;

        function closeResults() {
            resultsBox.classList.add('d-none');
        }

        function clearValue() {
            if (searchInput.value !== selectedText) {
                valueInput.value = '';
            }
        }

        function getParentValue() {
            if (!parentSelector) {
                return '';
            }

            var parentInput = document.querySelector(parentSelector);

            return parentInput ? parentInput.value : '';
        }

        function resetDependents() {
            document.querySelectorAll('[data-parent="#' + valueInput.id + '"]').forEach(function (child) {
                var childSearch = child.querySelector('[data-search-input]');
                var childValue = child.querySelector('[data-value-input]');
                var childResults = child.querySelector('[data-search-results]');

                childSearch.value = '';
                childValue.value = '';
                childResults.innerHTML = '';
                childResults.classList.add('d-none');
            });
        }

        function chooseOption(option) {
            searchInput.value = option.text;
            valueInput.value = option.id;
            selectedText = option.text;

            if (fillTargetSelector && fillField && option[fillField] !== undefined) {
                var fillTarget = document.querySelector(fillTargetSelector);

                if (fillTarget && !fillTarget.value) {
                    fillTarget.value = option[fillField];
                }
            }

            searchInput.setCustomValidity('');
            searchInput.dispatchEvent(new Event('change'));
            resetDependents();
            closeResults();
        }

        function renderOptions(items) {
            resultsBox.innerHTML = '';

            if (!items.length) {
                resultsBox.innerHTML = '<div class="list-group-item text-muted">No results found</div>';
                resultsBox.classList.remove('d-none');
                return;
            }

            items.forEach(function (option) {
                var button = document.createElement('button');
                button.type = 'button';
                button.className = 'list-group-item list-group-item-action';
                button.textContent = option.text;
                button.addEventListener('mousedown', function (event) {
                    event.preventDefault();
                    chooseOption(option);
                });
                resultsBox.appendChild(button);
            });

            resultsBox.classList.remove('d-none');
        }

        function renderMessage(message) {
            resultsBox.innerHTML = '<div class="list-group-item text-muted">' + message + '</div>';
            resultsBox.classList.remove('d-none');
        }

        function localSearch(query) {
            return options.filter(function (option) {
                return option.text.toLowerCase().indexOf(query.toLowerCase()) !== -1;
            }).slice(0, 10);
        }

        function ajaxSearch(query) {
            var params = new URLSearchParams();
            params.set('q', query);

            if (parentParam) {
                params.set(parentParam, getParentValue());
            }

            return fetch(url + '?' + params.toString(), {
                headers: { 'Accept': 'application/json' },
            }).then(function (response) {
                return response.json();
            });
        }

        function search() {
            var query = searchInput.value.trim();

            clearValue();

            if (parentRequired && !getParentValue()) {
                renderMessage('Select parent field first');
                return;
            }

            if (url) {
                ajaxSearch(query).then(renderOptions);
                return;
            }

            renderOptions(localSearch(query));
        }

        searchInput.addEventListener('input', search);
        searchInput.addEventListener('focus', search);
        searchInput.addEventListener('blur', function () {
            setTimeout(closeResults, 150);
        });

        searchInput.form?.addEventListener('submit', function (event) {
            if (searchInput.required && !valueInput.value) {
                searchInput.setCustomValidity('Please select a value from the list.');
                searchInput.reportValidity();
                event.preventDefault();
                return;
            }

            searchInput.setCustomValidity('');
        });
    });
});
