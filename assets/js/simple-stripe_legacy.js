



function simple_stripe_checkin(e, data_box, message_box) {

  var amount = Number(data_box.value);

  
  
  if (['BIF', 'CLP', 'DJF', 'GNF', 'JPY', 'KMF', 'KRW', 'MGA', 'PYG', 'RWF', 'UGX', 'VND', 'VUV', 'XAF', 'XOF', 'XPF'].indexOf(data_box.getAttribute("data-currency")) === -1) {
    amount = amount * 100;
  }


  
  if (data_box.getAttribute("data-minimum_amount") !== '' && amount < Number(data_box.getAttribute("data-minimum_amount"))) {

    message_box.style.display = 'inline-block';
    message_box.style.border = '4px solid #ffc937';
    message_box.style.backgroundColor = '#fff9f0';
    message_box.innerHTML = simple_stripe_legacy.msg_minimum_amount;
    return;

  }

  message_box.style.display = 'none';
  message_box.innerHTML = '';
  message_box.style.border = 'none';


  var handler = simple_stripe_checkout(data_box, message_box, amount);

  handler.open({
    name: data_box.getAttribute("data-name"),
    description: data_box.getAttribute("data-description"),
    amount: amount,
    currency: data_box.getAttribute("data-currency"),
    panelLabel: data_box.getAttribute("data-panelLabel"),
  });

  e.preventDefault();

}

function simple_stripe_checkout(data_box, message_box, amount) {

  message_box.innerHTML = '';
  message_box.style.border = 'none';
  message_box.style.backgroundColor = '';

  var handler = StripeCheckout.configure({
    key: simple_stripe_legacy.publishable_key,
    image: data_box.getAttribute("data-image"),
    locale: data_box.getAttribute("data-locale"),
    zipCode: simple_stripe_toBoolean(data_box.getAttribute("data-zipCode")),
    shippingAddress: simple_stripe_toBoolean(data_box.getAttribute("data-shippingAddress")),
    billingAddress: simple_stripe_toBoolean(data_box.getAttribute("data-shippingAddress")),
    allowRememberMe: simple_stripe_toBoolean(data_box.getAttribute("data-allowRememberMe")),
    token: function (token) {
      var data = {
        action: 'simple_stripe_ajax',
        nonce: simple_stripe_legacy.nonce,
        token: token.id,
        email: token.email,
        amount: amount,
        description: data_box.getAttribute("data-description"),
        currency: data_box.getAttribute("data-currency"),
      };

      var xhr = new XMLHttpRequest();



      xhr.onreadystatechange = function () {
        message_box.style.display = 'inline-block';
        if (xhr.readyState === 4) {
          
          
          
          var msg = JSON.parse(xhr.response);

          if (xhr.status === 200 && msg['message'] === "OK") {
            
            message_box.style.border = '4px solid #7cbc77';
            message_box.style.backgroundColor = '#f0f9ef';
            message_box.innerHTML = simple_stripe_legacy.msg_successful;

            
            if (simple_stripe_legacy.redirect_url_success_pay !== '') {
              document.location.href = simple_stripe_legacy.redirect_url_success_pay;
            }
          } else {
            
            message_box.style.border = '4px solid #f98a89';
            message_box.style.backgroundColor = '#fff5f5';
            message_box.innerHTML = simple_stripe_legacy.msg_failed + '<br>' + msg['message'];
          }
        } else {
          
          message_box.style.border = '4px solid #bdbabd';
          message_box.style.backgroundColor = '#eeeeee';
          message_box.innerHTML = simple_stripe_legacy.msg_connecting;
        }
      };

      xhr.open("POST", simple_stripe_legacy.admin_ajax, true);
      xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

      xhr.send(encodeURI(simple_stripe_encodeURI(data)));

    }
  });
  return handler;
}

function simple_stripe_encodeURI(obj) {
  var result = '',
    splitter = '';

  if (typeof obj === 'object') {
    Object.keys(obj).forEach(function (key) {
      result += splitter + key + '=' + encodeURIComponent(obj[key]);
      splitter = '&';
    });
  }
  return result;
}

function simple_stripe_amount_key(k) {
  
  if (!((k >= 48 && k <= 57) || (k >= 96 && k <= 105) || k == 8 || k == 46 || k == 39 || k == 37 || k == 110)) {
    return false;
  }
}

function simple_stripe_toBoolean(data) {
  if (data.toLowerCase() === 'true') {
    return true;
  } else {
    return false;
  }
}
