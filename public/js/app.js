document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-admin-driver-form]').forEach(function (form) {
        var roleSelect = form.querySelector('[data-user-role-select]');
        var licenseFields = form.querySelector('[data-driver-license-fields]');

        if (!roleSelect || !licenseFields) {
            return;
        }

        var licenseInputs = licenseFields.querySelectorAll('input');

        var syncLicenseFields = function () {
            var selectedRole = roleSelect.value.toLowerCase();
            var hiddenRoleInput = form.querySelector('input[name="user_role"]');

            if (hiddenRoleInput) {
                selectedRole = hiddenRoleInput.value.toLowerCase();
            }

            var isCabDriver = selectedRole === 'cab drivers';

            licenseFields.classList.toggle('d-none', !isCabDriver);
            licenseInputs.forEach(function (input) {
                input.disabled = !isCabDriver;
                input.required = false;

                if (!isCabDriver) {
                    input.value = '';
                }
            });
        };

        roleSelect.addEventListener('change', syncLicenseFields);
        syncLicenseFields();
    });
});
