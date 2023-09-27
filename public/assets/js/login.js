  // Đối tượng `Validator`
  // do validtaor là 1 Objetc nên ta truyền tham số là 1 options
  function Validator(options) {
    console.log(options);
    // Xử lý input nằm trong nhiều thẻ có thể call class err-mess từ thẻ cha form-group
    function getParent(element, selector) {   // chúng ta có element là input  , selector là class form-group
        while (element.parentElement) { // trong khi class bên ngoài input còn (input có tồn tại)
            if (element.parentElement.matches(selector)) {  // if lặp ra ngoài cùng thẻ chứa input k có form group thì lập ra thẻ cấp bên ngoài
                // đến khi gặp đc class form group . matches ktra xem element  (parentElement) có match vs  selector(forom-group)
                return element.parentElement; // trả về class cha 
            }
            element = element.parentElement; // else nếu thẻ cha k match vs form group thì element sẽ là thẻ đó để tranh treo trình duyệt
        }
        
    }

    

    var selectorRules = {};

    // Hàm thực hiện validate
    function validate(inputElement, rule) { //inputElement thẻ input , rule qui tắc 
        var errorElement = getParent(inputElement, options.formGroupSelector).querySelector(options.errorSelector); 
        // từ thẻ input và thẻ form của from 1 || 2 lấy ra class form-message
        // errorSelector: '.form-message', options thẻ form , formGroupSelector thẻ form 1 || 2
        var errorMessage;

        // Lấy ra các rules của selector
        var rules = selectorRules[rule.selector];
        
        // Lặp qua từng rule & kiểm tra
        for (var i = 0; i < rules.length; ++i) {
            switch (inputElement.type) { // chúng ta check type của thẻ input để trường hợp có radio check box vẫn có value
                case 'radio':
                case 'checkbox':
                    errorMessage = rules[i](
                        formElement.querySelector(rule.selector + ':checked')  // từ thẻ form lấy ra rule của css selector có checked
                    );
                    break;
                default:
                    errorMessage = rules[i](inputElement.value); // với the input thì lấy giá trị nhập ktra 
            }
            if (errorMessage) break;  // Nếu có lỗi thì dừng việc kiểm
        }
        
        if (errorMessage) {
            // khi có lỗi blur , nhập sai thì thêm text mess 
            errorElement.innerText = errorMessage;
            getParent(inputElement, options.formGroupSelector).classList.add('invalid'); // thêm class báo lỗi
        } else {
            // ngc lại thì text rỗng
            errorElement.innerText = '';
            getParent(inputElement, options.formGroupSelector).classList.remove('invalid');// delete class báo lỗi
        }

        return !errorMessage; // nếu Validate có lỗi trả ra false ngc lại trả ra true
    }

    // Lấy element của form cần validate
    var formElement = document.querySelector(options.form); // lấy form ra điể truy cập vào el con
    // var enableInputs = formElement.querySelectorAll('[name]');
    // console.log(enableInputs);
    // console.log(options.rules)
    if (formElement) {
        // Khi submit form
        formElement.onsubmit = function (e) {
            e.preventDefault(); // bỏ đi hành vi mặc định (chuyển trang khi sub)

            var isFormValid = true; 
            // khi Validator nhận đc giá trị  từ rules [] nên ta có thể dùng forech để lặp
            // Lặp qua từng rules và validate
            options.rules.forEach(function (rule) {
                var inputElement = formElement.querySelector(rule.selector); // chúng ta đi từ formElement ko phải document để tránh lấy ra những 
                // el của form khác(form 1 # form 2)
                // console.log(inputElement);
                var isValid = validate(inputElement, rule); // check user nhập . value = inputElement.value , hàm check (test function) : rule.test
                if (!isValid) { // nếu có el có l64i
                    isFormValid = false; // thì có lỗi
                }
            });

            var enableInputs = formElement.querySelectorAll('[name]');
            console.log(enableInputs);
            //Xử Lý khi ko có lỗi ở rule
            if (isFormValid) {
                // Trường hợp submit với javascript
                if (typeof options.onSubmit === 'function') {
                    var enableInputs = formElement.querySelectorAll('[name]'); // lấy ra tất cả thẻ có name = ""
                    var formValues = Array.from(enableInputs).reduce(function (values, input) { // vì enableInputs là 1 NodeList nên chuyển sang Arr
                        // để use phương thức Arr
                        
                        switch(input.type) { // loại input
                            case 'radio': 
                                values[input.name] = formElement.querySelector('input[name="' + input.name + '"]:checked').value;
                                // lấy value name của thẻ input đc check  
                                break;
                            case 'checkbox': // chọn nhiều cái
                                if (!input.matches(':checked')) { // nếu input ko đc check thì value rỗng trả ra giá trị
                                    values[input.name] = '';
                                    return values;
                                }
                                if (!Array.isArray(values[input.name])) { // nếu ko phải Arr thì value là arr rỗng
                                    values[input.name] = [];
                                }
                                values[input.name].push(input.value); // else thì đẩy value vào mảng
                                break;
                            case 'file':
                                values[input.name] = input.files;
                                break;
                            default:
                                values[input.name] = input.value;
                        }

                        return values;
                    }, {});
                    options.onSubmit(formValues);
                }
                // Trường hợp submit với hành vi mặc định
                else {
                    formElement.submit();
                }
            }
        }

        // Lặp qua mỗi rule và xử lý (lắng nghe sự kiện blur, input, ...)
        options.rules.forEach(function (rule) {

            // Lưu lại các rules cho mỗi input
            if (Array.isArray(selectorRules[rule.selector])) {
                selectorRules[rule.selector].push(rule.test);
            } else {
                selectorRules[rule.selector] = [rule.test];
            }

            var inputElements = formElement.querySelectorAll(rule.selector); // lấy ra all thẻ input (NodeList)

            Array.from(inputElements).forEach(function (inputElement) { // chuyển sang array  dùng foreach lặp 
               // Xử lý trường hợp blur khỏi input
                inputElement.onblur = function () {
                    validate(inputElement, rule); // lấy giá trị thẻ input call back xử lý rule
                }

                // Xử lý mỗi khi người dùng nhập vào input nếu blur ra ngoài báo err còn đang nhập k báo
                inputElement.oninput = function () {
                    // errorElement lấy value và thẻ form , lấy class báo l64i
                    var errorElement = getParent(inputElement, options.formGroupSelector).querySelector(options.errorSelector);
                    errorElement.innerText = ''; // gán vào 1 chưỡi rỗng 
                    getParent(inputElement, options.formGroupSelector).classList.remove('invalid'); // ko báo lỗi
                } 
            });
        });
    }

}



// Định nghĩa rules (Phương thức)
// Nguyên tắc của các rules:
// 1. Khi có lỗi => Trả ra message lỗi
// 2. Khi hợp lệ => Không trả ra cái gì cả (undefined)
Validator.isRequired = function (selector, message) {
    return {
        selector: selector, // khi đối số trả về giá trị gì thì selector nhận value đó
        test: function (value) { // nhận value user nhập
            return value.trim() ? undefined :  message || 'Vui lòng nhập trường này' // function test dùng để check
        }
    };
}

Validator.isEmail = function (selector, message) {
    return {
        selector: selector,
        test: function (value) {
            var regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            return regex.test(value) ? undefined :  message || 'Trường này phải là email'; 
        }
    };
}
// độ dài ít nhất của mk
Validator.minLength = function (selector, min, message) {
    return {
        selector: selector,
        test: function (value) {
            return value.length  >= min  ? undefined :  message || `Vui lòng nhập tối thiểu ${min} kí tự`;
        }
    };
}

Validator.isConfirmed = function (selector, getConfirmValue, message) { // func nhận 3 đối số 1 giá trị , 2 là func check giống nhau , 3 custom value
    return {
        selector: selector,
        test: function (value) {
            return value === getConfirmValue() ? undefined : message || 'Giá trị nhập vào không chính xác'; 
            // nếu có customvalue thì lấy còn k lấy value còn lại
        }
    }
}
