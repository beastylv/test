window.addEventListener('DOMContentLoaded', () => {
    const field = document.forms['form']['email'];
    const checkbox = document.querySelector('.checkbox');

    field.addEventListener('input', () => {
        validate(field.value, checkbox);
    });

    checkbox.addEventListener('change', () => {
        validate(field.value, checkbox);
    });
});

function isEmail(email) {
    if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)) return true;
    
    return false;
}

function validate(value, checkbox) {
    const errorMessage = document.querySelector('.error');
    const submitButton = document.querySelector('.submit');
    const form = document.forms['form'];

    submitButton.disabled = true;
    
    form.style.border = '1px solid #B80808';
    form['email'].style['border-left'] = '4px solid #B80808';

    if (value === '') return errorMessage.innerHTML = 'Email address is required';

    if (!isEmail(value)) return errorMessage.innerHTML = 'Please provide a valid e-mail address';

    if (value.endsWith('.co')) return errorMessage.innerHTML = 'We are not accepting subscriptions from Colombia emails';

    if (!checkbox.checked) return errorMessage.innerHTML = 'You must accept the terms and conditions';

    errorMessage.innerHTML = '';

    submitButton.disabled = false;

    form.style.border = '1px solid #E3E3E4';
    form['email'].style['border-left'] = '4px solid #4066A5';
}