export const escapeTag = (content) => {
    return content.replace(/(<([^>]+)>)/gi, "");
};

export const removeHtmlTags = (inputString) => {
    // Regular expression to match HTML tags
    const regex = /<[^>]+>/g;

    // Replace HTML tags with an empty string
    return inputString.replace(regex, '');
  }

export const removeHtmlEntities = (inputString) => {
    const doc = new DOMParser().parseFromString(inputString, 'text/html');
    return doc.documentElement.textContent;
}

export const clearHtmlString = (inputString) => {
    let value = removeHtmlTags(inputString);
    return removeHtmlEntities(value);
}

export const checkExstension = (fileName, exts) => {
    return new RegExp("(" + exts.join("|").replace(/\./g, "\\.") + ")$").test(
        fileName
    )
}
