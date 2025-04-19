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


function getSocialIcons(type) {
    const socialIconsPath = [];
    const socialIconPath = `${__SAP_SITE_URL}/assets/images/social-icon`;

    if (type.includes('twitter')) {
        socialIconsPath.push(`${socialIconPath}/twitter.svg`);
    }

    if (type.includes('telegram')) {
        socialIconsPath.push(`${socialIconPath}/telegram.svg`);
    }

    if (type.includes('instagram')) {
        socialIconsPath.push(`${socialIconPath}/instagram.svg`);
    }

    if (type.includes('facebook')) {
        socialIconsPath.push(`${socialIconPath}/facebook.png`);
    }

    if (type.includes('youtube')) {
        socialIconsPath.push(`${socialIconPath}/youtube.svg`);
    }

    if (type.includes('pinterest')) {
        socialIconsPath.push(`${socialIconPath}/pinterest.png`);
    }

    if (type.includes('linkedin')) {
        socialIconsPath.push(`${socialIconPath}/linkedin.png`);
    }

    if (type.includes('tumblr')) {
        socialIconsPath.push(`${socialIconPath}/tumblr.png`);
    }

    if (type.includes('reddit')) {
        socialIconsPath.push(`${socialIconPath}/Reddit.png`);
    }

    if (type.includes('blogger')) {
        socialIconsPath.push(`${socialIconPath}/blogger.svg`);
    }

    if (type.includes('wordpress')) {
        socialIconsPath.push(`${socialIconPath}/WordPress.svg`);
    }

    if (type.includes('google')) {
        socialIconsPath.push(`${socialIconPath}/Google.png`);
    }

    return socialIconsPath;
}
