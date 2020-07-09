const KEY_CODE_ENTER = 13;

window.addEventListener('load', () => {
  const urlInput = document.getElementById('url-input');
  const shortenButton = document.getElementById('shorten-button');
  const responseMessage = document.getElementById('response-message');

  urlInput.addEventListener('keyup', event => {
    if (event.keyCode === KEY_CODE_ENTER) {
      event.preventDefault();
      shortenButton.click();
    }
  });

  shortenButton.addEventListener('click', () => {
    let error = false;

    fetch(`/url-shortener.php?url=${urlInput.value}`)
      .then(res => {
        error = !res.ok;
        return res.text();
      })
      .then(res => {
        if (error) {
          responseMessage.classList.add('error');
          responseMessage.innerText = `Error: ${res}`;
        } else {
          responseMessage.classList.remove('error');
          responseMessage.innerText = res;
        }
      });
  });
});
