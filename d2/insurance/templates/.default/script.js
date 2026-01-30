BX.ready(function() {
    var result = new BX.MaskedInput({
        mask: '+7 999 999 99 99', 
        input: BX('phone'),
        placeholder: '_' 
    });
    result.setValue('9000000000'); 

    var resultPass = new BX.MaskedInput({
        mask: '9999 999999', 
        input: BX('passport'),
        placeholder: '_' 
    });
    resultPass.setValue('XXXX XXXXXX');


    let button = document.querySelector(".insurance-form-submit");
    
    button.addEventListener("click", function(e){
        e.preventDefault();

        let data = document.querySelectorAll("input.insurance-form-input[required]");
        let flag = false;
        let path = document.querySelector('input[name="path"]').value;
        let productId = document.querySelector('input[name="product"]').value;
        let notification = document.querySelector(".insuranse-form-item--notification");

        data.forEach(dataElem=>{
            if(dataElem.value == ""){
                message = "Заполнены не все поля ввода";
                notification.innerHTML = message;
                notification.style.color = "red";
                flag = false;
            }
            else{
                flag = true;
            }
        });

        if(flag != false){
            let data = {
                surname: $("input[name='surname']").val(),
                name: $("input[name='name']").val(),
                birthday: $("input[name='birthday']").val(),
                phone: $("input[name='phone']").val(),
                address: $("input[name='address']").val(),
                passport: $("input[name='passport']").val(),
                productId: productId
            };
            
            fetch(path + "/getPolis.php", {
                method: "POST",
                body: new URLSearchParams(data)
            })
            .then(res => res.blob())
            .then(blob => {
                let url = window.URL.createObjectURL(blob);
                let a = document.createElement('a');
                a.href = url;
                a.download = 'policy.pdf';
                document.body.appendChild(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);
                notification.innerHTML = "Сертификат успешно оформлен";
                notification.style.color = "green";

            });

            document.querySelectorAll(".insurance-form-input").forEach(input => {
                input.value = "";
            });
        }
    })
});

