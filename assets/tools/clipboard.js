window.copyText = (text) => {
    return new Promise((resolve, reject) => {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text)
                .then(() => {
                    resolve('复制成功')
                })
                .catch(() => {
                    reject('复制失败')
                })
        } else {
            let textarea = document.createElement('textarea')
            textarea.value = text
            document.body.appendChild(textarea)
            // 选中
            textarea.select()
            textarea.focus()
            document.execCommand('copy')
            document.body.removeChild(textarea)
            resolve('复制成功')
        }
    })
  }
