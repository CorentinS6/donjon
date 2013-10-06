function nospam (email) {
  window.location = 'mailto:' + email.replace (/ \[at\] /, '@').replace (/ \[dot\] /g, '.');
}
