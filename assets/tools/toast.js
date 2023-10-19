const DEFAULT_OPTIONS = {
  duration: 2000,
  message: '',
}

const toast = {
  show: (options) => {
    options = options || DEFAULT_OPTIONS
    if (typeof options === 'string') {
      options = {duration: 2000, message: options}
    }
    const html = `<div class="fwx-toast">${options.message}</div>`

    const tempDiv = document.createElement('div')
    tempDiv.innerHTML = html
    const elem = tempDiv.firstChild
    document.body.appendChild(elem)
    setTimeout(() => {
      document.body.removeChild(elem)
    }, options.duration)
  }
}

window.toast = toast
