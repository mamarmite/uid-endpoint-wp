async function copyCodeHandler(button) {
  const codeElement = document.getElementById('schemaJsonLd');
  const text = codeElement.textContent;
  const originalText = button.textContent;

  try {
    await navigator.clipboard.writeText(text);

    button.textContent = 'Copié!';
    button.classList.add('copied');

    setTimeout(() => {
      button.textContent = originalText;
      button.classList.remove('copied');
    }, 2000);
  } catch (err) {
    console.error('Failed to copy text: ', err);
    button.textContent = 'Error';
    setTimeout(() => {
      button.textContent = originalText;
    }, 2000);
  }
}
