
var stripe = Stripe(simple_stripe_new.publishable_key); // test publishable API key
var elements = stripe.elements();

var ss_input_amount,
  ss_uniqid,
  hidepostalcode;

var simple_stripe_amount = document.getElementsByClassName('simple_stripe_amount');
if (simple_stripe_amount[0].dataset.hidepostalcode == 'true') {
  hidepostalcode = true;
} else {
  hidepostalcode = false;
}


var card = elements.create('card', {
  //iconStyle: 'solid',
  style: {
    base: {
      //iconColor: '#c4f0ff',
      //color: '#000',
      //fontWeight: 500,
      //fontFamily: 'Roboto, Open Sans, Segoe UI, sans-serif',
      //fontSize: '16px',
      //fontSmoothing: 'antialiased',
      //':-webkit-autofill': {
      //color: '#fce883',
      //},
      //'::placeholder': {
      //color: '#87BBFD',
      //},
    },
    invalid: {
      //iconColor: '#FFC7EE',
      //color: '#FFC7EE',
    },
  },
  hidePostalCode: hidepostalcode,
});

card.mount('.ss_card-element');


// Handle events and errors
card.addEventListener('change', function (event) {

  var displayError = document.getElementById('card-errors_' + card._parent.parentElement.parentElement[0].getAttribute("data-uniqid"));
  if (event.error) {
    displayError.textContent = event.error.message;
  } else {
    displayError.textContent = '';
  }
});

function stripeTokenHandler(token,paymentMethod_id) {

  var amount = Number(ss_input_amount.value);

  var message_box = document.getElementById('card-status_' + ss_uniqid);

  
  
  if (['BIF', 'CLP', 'DJF', 'GNF', 'JPY', 'KMF', 'KRW', 'MGA', 'PYG', 'RWF', 'UGX', 'VND', 'VUV', 'XAF', 'XOF', 'XPF'].indexOf(ss_input_amount.getAttribute("data-currency")) === -1) {
    amount = amount * 100;
  }

  message_box.style.display = 'inline-block';

  
  if (ss_input_amount.getAttribute("data-minimum_amount") !== '' && amount < Number(ss_input_amount.getAttribute("data-minimum_amount"))) {


    message_box.style.border = '4px solid #ffc937';
    message_box.style.backgroundColor = '#fff9f0';
    message_box.innerHTML = simple_stripe_new.msg_minimum_amount;

    document.getElementById('pay_' + ss_uniqid).style.display = 'inline-block';

    return;

  }

  message_box.innerHTML = '';
  message_box.style.border = 'none';






  // Insert the token ID into the form so it gets submitted to the server
  var form = document.getElementById('payment-form_' + ss_uniqid);
  var hiddenInput = document.createElement('input');
  hiddenInput.setAttribute('type', 'hidden');
  hiddenInput.setAttribute('name', 'stripeToken');
  hiddenInput.setAttribute('value', token.id);
  form.appendChild(hiddenInput);

  // Submit the form
  //form.submit();

  //console.log(ss_input_amount.value);

  var data = {
    action: 'simple_stripe_ajax',
    nonce: simple_stripe_new.nonce,
    token: token.id,
    paymentMethod_id:paymentMethod_id,
    //email: token.email,
    amount: parseInt(amount),
    description: ss_input_amount.getAttribute('data-description'),
    currency: ss_input_amount.getAttribute('data-currency'),
    return_url: ss_input_amount.getAttribute('data-return_url'),
    here_url:ss_input_amount.getAttribute('data-here_url'),
  };




  var xhr = new XMLHttpRequest();



  xhr.onreadystatechange = function () {
    //message_box.style.display = 'block';
    
    if (xhr.readyState === 4) {
      
      
      
      var msg = JSON.parse(xhr.response);
      
      
      if (xhr.status === 200 && msg['message'] === "OK") {
        
        message_box.style.border = '4px solid #7cbc77';
        message_box.style.backgroundColor = '#f0f9ef';
        message_box.innerHTML = simple_stripe_new.msg_successful;
        document.getElementById('pay_' + ss_uniqid).style.display = 'inline-block';

        
        if (simple_stripe_new.redirect_url_success_pay !== '') {
          document.location.href = simple_stripe_new.redirect_url_success_pay;
        }
      } else {
        
        message_box.style.border = '4px solid #f98a89';
        message_box.style.backgroundColor = '#fff5f5';
        message_box.innerHTML = simple_stripe_new.msg_failed + '<br>' + msg['message'];
        document.getElementById('pay_' + ss_uniqid).style.display = 'inline-block';
      }
    } else {
      
      message_box.style.border = '4px solid #bdbabd';
      message_box.style.backgroundColor = '#eeeeee';
      message_box.innerHTML = simple_stripe_new.msg_connecting;
    }
  };

  xhr.open("POST", simple_stripe_new.admin_ajax, true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  xhr.send(encodeURI(simple_stripe_new_encodeURI(data)));

}






// Create a token when the form is submitted.
/*
    var form = document.getElementById('payment-form');
    form.addEventListener('submit', function(e) {
      console.log(e)
      e.preventDefault();
      createToken();
    });
    */


function simple_stripe_createToken(e) {
  
  //ss_input_amount = e.path[2][0];

  //ss_input_amount = document.getElementById(e.target.id.replace('pay_', 'payment-form_'));
  var pay_btn = document.getElementById(e.target.id);

  ss_input_amount = document.querySelector('#' + e.target.id.replace('pay_', 'payment-form_') + ' .simple_stripe_amount');
  
  ss_uniqid = ss_input_amount.getAttribute('data-uniqid');

  
  
  pay_btn.style.display = 'none';

  e.preventDefault();

  stripe.createToken(card).then(function (result) {
    if (result.error) {
      // Inform the user if there was an error
      var errorElement = document.getElementById('card-errors_' + ss_uniqid);
      errorElement.textContent = result.error.message;
      
      pay_btn.style.display = 'inline-block';

    } else {

      stripe.createPaymentMethod("card", card).then(function (result2) {
          if (result2.error) {
          } else {
            // Send the token to your server
            stripeTokenHandler(result.token , result2.paymentMethod.id);
          }
        })
        .catch(function () {
        });



    }
  });

}


function simple_stripe_new_encodeURI(obj) {
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





