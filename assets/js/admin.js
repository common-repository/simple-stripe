

function simple_stripe_get_select_option_value(id) {
  var e = document.getElementById(id);
  return e.options[e.selectedIndex].value;
}


function simple_stripe_get_radio_value(name) {

  var e = document.getElementsByName(name);

  for (var i = 0; i < e.length; i++) {
    if (e[i].checked) {
      return e[i].value;
      break;
    }
  }

}



function simple_stripe_custom_shortcode() {
  var temp = '',
    code = '';

  temp = document.getElementById('simple_stripe_logo_image_custom').value;

  if (temp !== '') {
    code += ' image="' + temp + '"';
  }

  temp = simple_stripe_get_select_option_value('simple_stripe_currency_custom');

  if (temp !== 'default') {
    code += ' currency="' + temp + '"';
  }

  temp = document.getElementById('simple_stripe_amount_custom').value;

  if (temp !== '') {
    code += ' amount="' + temp + '"';
  }

  temp = document.getElementById('simple_stripe_name_custom').value;

  if (temp !== '') {
    code += ' name="' + temp + '"';
  }

  temp = document.getElementById('simple_stripe_description_custom').value;

  if (temp !== '') {
    code += ' description="' + temp + '"';
  }

  temp = document.getElementById('simple_stripe_panellabel_custom').value;

  if (temp !== '') {
    code += ' panellabel="' + temp + '"';
  }

  temp = simple_stripe_get_select_option_value('simple_stripe_locale_custom');

  if (temp !== 'default') {
    code += ' locale="' + temp + '"';
  }

  temp = simple_stripe_get_radio_value('simple_stripe_address_custom');

  if (temp !== 'default') {
    code += ' address="' + temp + '"';
  }

  temp = simple_stripe_get_radio_value('simple_stripe_zip_custom');

  if (temp !== 'default') {
    code += ' zip="' + temp + '"';
  }

  temp = simple_stripe_get_radio_value('simple_stripe_remember_custom');

  if (temp !== 'default') {
    code += ' remember="' + temp + '"';
  }

  temp = simple_stripe_get_radio_value('simple_stripe_amount_mode_custom');

  if (temp !== 'default') {
    code += ' amount_mode="' + temp + '"';
  }

  temp = document.getElementById('simple_stripe_open_button_custom').value;

  if (temp !== '') {
    code += ' open_button="' + temp + '"';
  }

  document.getElementById('simple_stripe_custom_shortcode').value = '[simple_stripe' + code + ']';

}

var simple_stripe_input = document.querySelectorAll('.simple_stripe_input');
//console.log(simple_stripe_input);
simple_stripe_input.forEach(function (box) {
  box.addEventListener('input', function (evt) {
    simple_stripe_custom_shortcode();
  });
});






window.addEventListener('load', function () {

  var ss_loading = document.getElementById('ss_loading'),
    ss_loading_bg = document.getElementById('ss_loading_bg');

  simple_stripe_fadeOut(ss_loading);
  simple_stripe_fadeOut(ss_loading_bg);

  if (document.getElementById('simple_stripe_submit')) {

    document.getElementById('simple_stripe_submit').onclick = function () {
      simple_stripe_fadeIn(ss_loading);
      simple_stripe_fadeIn(ss_loading_bg);
      document.simple_stripe_edit_form.submit();
    };

  }




  document.getElementById('simple_stripe_logo_image_clear').onclick = function (e) {
    document.getElementById('simple_stripe_logo_image').value = '';
    document.getElementById('simple_stripe_image_div').style.backgroundImage = '';

    
    if (document.getElementById('simple_stripe_edit_shortcode'))
      simple_stripe_custom_shortcode();
  };
  
  var simple_stripeMedia;

  document.getElementById('simple_stripe_image_div').onclick = function (e) {
    e.preventDefault();
    
    if (simple_stripeMedia) {
      simple_stripeMedia.open();
      return;
    }
    
    simple_stripeMedia = wp.media.frames.file_frame = wp.media({
      title: simple_stripe_admin.select_image,
      library: {
        type: "image"
      },
      button: {
        text: simple_stripe_admin.select_image
      },
      multiple: false
    });

    
    simple_stripeMedia.on('select', function () {
      var attachment = simple_stripeMedia.state().get('selection').first().toJSON();
      document.getElementById('simple_stripe_logo_image').value = attachment.url;
      document.getElementById('simple_stripe_image_div').style.backgroundImage = 'url("' + attachment.url + '")';

      
      if (document.getElementById('simple_stripe_edit_shortcode'))
        simple_stripe_custom_shortcode();

    });
    
    simple_stripeMedia.open();
  };


  document.getElementById('simple_stripe_logo_image_clear_custom').onclick = function (e) {
    document.getElementById('simple_stripe_logo_image_custom').value = '';
    document.getElementById('simple_stripe_image_div_custom').style.backgroundImage = '';

    
    if (document.getElementById('simple_stripe_edit_shortcode'))
      simple_stripe_custom_shortcode();
  };
  
  var simple_stripeMedia_custom;

  document.getElementById('simple_stripe_image_div_custom').onclick = function (e) {
    console.log('hi')
    e.preventDefault();
    
    if (simple_stripeMedia_custom) {
      simple_stripeMedia_custom.open();
      return;
    }
    
    simple_stripeMedia_custom = wp.media.frames.file_frame = wp.media({
      title: simple_stripe_admin.select_image,
      library: {
        type: "image"
      },
      button: {
        text: simple_stripe_admin.select_image
      },
      multiple: false
    });

    
    simple_stripeMedia_custom.on('select', function () {
      var attachment = simple_stripeMedia_custom.state().get('selection').first().toJSON();
      document.getElementById('simple_stripe_logo_image_custom').value = attachment.url;
      document.getElementById('simple_stripe_image_div_custom').style.backgroundImage = 'url("' + attachment.url + '")';

      
      if (document.getElementById('simple_stripe_edit_shortcode'))
        simple_stripe_custom_shortcode();

    });
    
    simple_stripeMedia_custom.open();
  };
});


function simple_stripe_fadeOut(el) {
  el.style.opacity = 1;

  (function fade() {
    if ((el.style.opacity -= .1) < 0) {
      el.style.display = "none";
    } else {
      requestAnimationFrame(fade);
    }
  })();
}

function simple_stripe_fadeIn(el, display) {
  el.style.opacity = 0;
  el.style.display = display || "block";

  (function fade() {
    var val = parseFloat(el.style.opacity);
    if (!((val += .1) > 1)) {
      el.style.opacity = val;
      requestAnimationFrame(fade);
    }
  })();
}

var simple_stripe_timeoutID;

function simple_stripe_stopTimeout() {
  var pop_up_message = document.getElementById('ss_pop_up_message');
  pop_up_message.classList.add('inactive');
  clearTimeout(simple_stripe_timeoutID);
  setTimeout(function () {
    pop_up_message.classList.remove('inactive');
  }, 100);

}

function simple_stripe_pop_up_message(message, bg_color) {
  var today = new Date();
  
  if (typeof simple_stripe_timeoutID !== 'undefined')
    simple_stripe_stopTimeout();
  if (bg_color === '') bg_color = '#222';
  var pop_up_message = document.getElementById('ss_pop_up_message');
  pop_up_message.style.backgroundColor = bg_color;
  pop_up_message.classList.add('active');
  pop_up_message.innerHTML = message;
  simple_stripe_timeoutID = setTimeout(function () {
    pop_up_message.classList.remove('active');
  }, 4000);
}
