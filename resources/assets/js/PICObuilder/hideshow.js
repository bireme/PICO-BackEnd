
////PUBLIC FUNCTIONS

export function isHiddenBootstrapObj(Obj) {
    return $(Obj).hasClass('d-none');
}

export function showBootstrapObj(Obj) {
    if (isHiddenBootstrapObj(Obj)) {
        $(Obj).removeClass('d-none');
    }
}

export function hideBootstrapObj(Obj) {
    if (!(isHiddenBootstrapObj(Obj))) {
        $(Obj).addClass('d-none');
    }
}

////PRIVATE FUNCTIONS
