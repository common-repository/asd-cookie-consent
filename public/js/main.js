/* globals cookie_panel */

document.addEventListener('DOMContentLoaded', function () {
  InitCookiePolicy()
})

function InitCookiePolicy () {
  var staticPanel = false
  var showLink = false

  if (cookie_panel.position === 'top_pushdowm') {
    staticPanel = true
    cookie_panel.position = 'top'
  }

  if (cookie_panel.show_link === '1') {
    showLink = true
  } else {
    showLink = false
  }

  window.cookieconsent.initialise({
    'position': cookie_panel.position,
    'theme': cookie_panel.theme,
    'static': staticPanel,
    'cookie': { 
      'name': 'asd_cookieconsent',
      'path': '/',
      'domain': '',
      'expiryDays': parseInt(cookie_panel.expiry),
     },
     'cookieconsent': {
        'status': 'allow'
     },
    'palette': {
      'popup': {
        'background': cookie_panel.back_color,
        'text': cookie_panel.text_color
      },
      'button': {
        'background': cookie_panel.btn_color,
        'text': cookie_panel.btn_text_color
      }
    },
    'showLink': showLink,
    'content': {
      'dismiss': cookie_panel.btn_text,
      'allow': 'Allow',
      'deny': 'deny',
      'message': cookie_panel.message,
      'link': cookie_panel.text_cookie_info,
      'href': cookie_panel.href_cookie_info
    }
  })
}
