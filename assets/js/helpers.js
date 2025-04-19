function isImage(src) {
    const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
    const ext = src.split('.').pop().toLowerCase().split(/\#|\?/)[0];
    return imageExtensions.includes(ext);
}

function isVideo(src) {
    const videoExtensions = ['mp4', 'webm', 'ogg', 'mov', 'avi', 'mkv'];
    const ext = src.split('.').pop().toLowerCase().split(/\#|\?/)[0];
    return videoExtensions.includes(ext);
}