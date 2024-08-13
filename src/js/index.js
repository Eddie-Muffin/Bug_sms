import LoginForm  from '../modules/login_class.js';

window.addEventListener('DOMContentLoaded', (event) =>{
    const txtD = document.querySelector('.txtD');
    
    setTimeout(()=>{
        txtD.style.opacity = '1';
        // txtD.style.classList.add('settingIn');

    }, 1000);
});

function submitBtn()
{
var submit = document.getElementById('submit');
submit.style.background = "purple";
submit.style.color = "white";
submit.style.padding =".5em";
submit.style.position = "relative";
submit.style.right = "-110px";
submit.style.border ="0";
submit.style.borderRadius = "20px";
submit.style.fontWeight = "600";
}

submitBtn();




const Leffect = new LoginForm('loginForm','formId', 'options', ['fname', 'pwd']);
Leffect.selectInputs(); 