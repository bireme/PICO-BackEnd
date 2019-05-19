function getPICOinfo() {
    return [MessageCode(141), MessageCode(142), MessageCode(143), MessageCode(144), MessageCode(145)];
}

function getFieldListOptions() {
    return [MessageCode(121), MessageCode(122), MessageCode(123), MessageCode(124)];
}

function getLanguagesInGlobalLang() {
    return [MessageCode(201), MessageCode(202), MessageCode(203), MessageCode(204)];
}

function getLanguagesInNative() {
    return [MessageCode(201, 0), MessageCode(202, 1), MessageCode(203, 2), MessageCode(204, 3)];
}


function getMessageTitles() {
    return ['ERROR', MessageCode(102), MessageCode(103), MessageCode(104), MessageCode(106)];
}

function getPICOElements() {
    return [MessageCode(111), MessageCode(112), MessageCode(113), MessageCode(114), MessageCode(115), MessageCode(116)];
}

function getLanguage() {
    return globalLanguage
}
