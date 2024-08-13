class LoginForm {
    #name;
    #id_number;
    #input_feilds;
    #selected_option;
    #identity;

    constructor(name, id_number, selected_option, input_feilds) {
        this.#name = name;
        this.#id_number = id_number;
        this.#input_feilds = input_feilds;
        this.#selected_option = selected_option;
        this.#identity = ''; // To store the selected identity
    }

    //getters
    get FormDetails() {
        return {
            name: this.#name,
            id: this.#id_number,
            field: this.#input_feilds,
            selectedOption: this.#selected_option,
            identity: this.#identity
        }
    }

    //setters
    set user(value) {
        this.#name = value;
    }
    set idNum(value) {
        this.#id_number = value;
    }
    set inputFields(value) {
        this.#input_feilds = value;
    }
    set optionSelect(value) {
        this.#selected_option = value;
    }
    set identity(value) {
        this.#identity = value;
    }

    //methods implementation

    inputFieldsEnable(space) {
        for (let i = 0; i < space.length; i++) {
            const field = document.getElementById(space[i]);
            if (field) {
                field.disabled = false;
            }
        }
    }

    inputFieldsDisable() {
        for (let i = 0; i < this.#input_feilds.length; i++) {
            const field = document.getElementById(this.#input_feilds[i]);
            if (field) {
                field.disabled = true;
            }
        }
    }

    clearInputFields() {
        for (let i = 0; i < this.#input_feilds.length; i++) {
            const fields = document.getElementById(this.#input_feilds[i]);
            if (fields) {
                fields.value = "";
            }
        }
    }

    true_selection(option) {
        console.log(`Selecting ${option}`);
        this.#identity = option; // Capture the selected identity
        this.inputFieldsDisable();
        this.clearInputFields();

        switch (option) {
            case 'student':
                this.inputFieldsEnable(['fname', 'pwd']);
                break;
            case 'teacher':
                this.inputFieldsEnable(['fname', 'pwd']);
                break;
            case 'administrator':
                this.inputFieldsEnable(['fname', 'pwd']);
                break;
            case 'lists':
                this.inputFieldsDisable();
                break;
            default:
                this.inputFieldsDisable();
                break;
        }

        this.buttonState();
    }

    buttonState() {
        let activate_feilds = true;
        for (let i = 0; i < this.#input_feilds.length; i++) {
            const f = document.getElementById(this.#input_feilds[i]);
            if (f && !f.disabled && f.value.trim() === '') {
                activate_feilds = false;
                break;
            }
        }

        const submitButton = document.getElementById('submit');
        if (submitButton) {
            const opSelect = document.getElementById(this.#selected_option);
            if (opSelect && (opSelect.value === 'lists' || !activate_feilds)) {
                submitButton.disabled = true;
                submitButton.style.background = '#d3d3d3';
                submitButton.style.cursor = 'not-allowed';
            } else {
                submitButton.disabled = false;
                submitButton.style.background = 'purple';
                submitButton.style.cursor = 'pointer';
            }
        }
    }

    selectInputs() {
        const opSelect = document.getElementById(this.#selected_option);
        if (opSelect) {
            console.log(`default value : ${opSelect.value}`);
            this.true_selection(opSelect.value);

            opSelect.addEventListener('change', () => {
                console.log(`default value changed to: ${opSelect.value}`);
                this.true_selection(opSelect.value);
            });
        }

        for (let i = 0; i < this.#input_feilds.length; i++) {
            const fields = document.getElementById(this.#input_feilds[i]);
            if (fields) {
                fields.addEventListener('input', () => {
                    this.buttonState();
                });
            }
        }

        // Manually append the identity to the form data before submitting
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', (event) => {
                const hiddenIdentityField = document.createElement('input');
                hiddenIdentityField.type = 'hidden';
                hiddenIdentityField.name = 'options';
                hiddenIdentityField.value = this.#identity;
                form.appendChild(hiddenIdentityField);
            });
        }
    }
}

export default LoginForm;

