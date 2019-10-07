export function getPICOnumFromObjId(obj) {
    return ($(obj).attr('id')).substr(-1);
}

///////////////////////////////////////////////

export function setImproveSearchWords(PICOnum, value) {
    $('#datainput' + PICOnum).attr('data-improve', value);
}

export function setOldDescriptors(PICOnum, value) {
    $('#datainput' + PICOnum).attr('data-olddescriptors', value);
}

export function setPreviousImproveQuery(PICOnum, value) {
    $('#datainput' + PICOnum).attr('data-previous-improve-query', value);
}

export function setnewQuery(PICOnum, newQuery) {
    if (PICOnum === 6) {
        $('#FinalSearchDetails').val(newQuery);
    } else {
        $('#datainput' + PICOnum).val(newQuery);
    }
}

export function setPreviousResults(results, PICOnum) {
    $('#datainput' + PICOnum).attr('data-previous-decs', results);
}

export function getobjDataInputVal(PICOnum) {
    return $('#datainput' + PICOnum).val();
}

export function getobjDataInput(PICOnum) {
    return $('#datainput' + PICOnum);
}

export function getPreviousResults(PICOnum) {
    return $('#datainput' + PICOnum).attr('data-previous-decs');
}

export function getFieldListOptionNum(PICOnum) {
    return $('#FieldList' + PICOnum).children("option:selected").index();
}

export function getOldDescriptors(PICOnum) {
    return $('#datainput' + PICOnum).attr('data-olddescriptors');
}

export function getPreviousImproveQuery(PICOnum) {
    return $('#datainput' + PICOnum).attr('data-previous-improve-query');
}

export function getImproveSearchWords(PICOnum) {
    return $('#datainput' + PICOnum).attr('data-improve');
}


//MODAL DATA

export function getQuerySplit() {
    return $('#modal1').find('.descriptorsform-querysplit').first().val();
}

export function queryinputvalue() {
    return $('#modal1').find('.descriptorsform-querysplit').first().val();
}

export function getImproveSearchTextArea() {
    return $('#modal3').find('textarea').val();
}

export function setModalContent(num, content) {
    if (num === 3) {
        $('#modal3').find('textarea').val(content);
    } else {
        $('#modal' + num).find('.modal-body').first().html(content);
    }

}

export function getModalPICOnum() {
    return $('#modal1').find('.descriptorsform-piconum').first().val();
}


//OTHER

export function getLanguages() {
    let langs = [];
    $(document).find('.languageCheckbox:checked').each(function () {
        langs.push($(this).attr('data-lang'));
    });
    return langs;
}
