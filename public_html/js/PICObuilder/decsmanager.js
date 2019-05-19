function ExpandDeCSConfig() {
    var msg = `
<div id="LanguageSection" class="container ">
    <div class="row">
        <div class="col-md-7 sidebar LanguageContainer">
            <label class="labelMain">
                ` + MessageCode(135) + `
            </label>
        </div>
        <div class="col-md-5 sidebar LanguageContainer text-left">
           <div class="LanguageInfoContainer">
                    <div class="CheckBoxRow" ><input type="checkbox" class="langCheck" name="Langs[]" value="en" checked /><label>English</label></div>
                    <div class="CheckBoxRow" ><input type="checkbox" class="langCheck" name="Langs[]" value="pt" /><label>Spanish</label></div>
                    <div class="CheckBoxRow" ><input type="checkbox" class="langCheck" name="Langs[]" value="es" /><label>Portuguese</label></div>
            </div>
        </div>
    </div>
</div`
    var langs = getLanguages();
    showInfoMessage('Config', msg, true, 'golanguage', setLanguagesOfModal, langs);
}

function DeCSMenuLanguages() {
    var langs = getLanguagesInGlobalLang();
    var count = 0;
    $('#modalinfo').find('.LanguageInfoContainer').first().find('label').each(function () {
        $(this).text(langs[count]);
        count++;
    });
}



function OnExpandDeCS(ExpandButton) {
    var langs = getLanguages();
    var PICOval = '#datainput' + ($(ExpandButton).attr('id')).substr(-1);
    var query = $(PICOval).val();
    var PICOnum = ($(ExpandButton).attr('id')).substr(-1);
    eventDeCSSearch(query, langs, PICOnum);
}

function setLanguagesFromModal(LangParent) {
    var langs = [];
    $(LangParent).find('.langCheck').each(function () {
        if ($(this).is(':checked')) {
            langs.push($(this).val());
        }
    });
    setLanguages(langs);
}

function setLanguagesOfModal(langArr) {

    var count = 0;
    $(document).find('.langCheck').each(function () {
        var index = langArr.indexOf($(this).val());
        if (index > -1) {
            $(this).attr('checked', true);
        } else {
            $(this).attr('checked', false);
        }
        count++;
    });
    DeCSMenuLanguages();
}

function eventDeCSSearch(query, langs, PICOnum) {
    var url = "ControllerEventDeCSSearch.php";
    var data = {
        query: query,
        langs: langs,
        PICOnum: PICOnum
    };
    POSTrequest(url, data, function (Data) {
        createDeCSMenu(Data);
        showDeCSMenu();
    });
}

function showDeCSMenu() {
    $('#modal').modal('show');
}


function createDeCSMenu(data) {
    var results = data.results;
    var HTMLDescriptors = data.HTMLDescriptors;
    var HTMLDeCS = data.HTMLDeCS;
    $('#modal').find('.modal-body').first().html(HTMLDescriptors);
    $('#modal2').find('.modal-body').first().html(HTMLDeCS);
}

function HideUnselectedDeCS() {
    var DeCSModalTitlePrefix = 'opcao';
    var DeCSModalTitlePostfix = '-tab';
    var num = 0;
    $('#modal').find('input.DescriptorCheckbox').each(function () {
        var identifier = ($(this).attr('id')).substring(10);
        var titleid = '#' + DeCSModalTitlePrefix + identifier + DeCSModalTitlePostfix;
        var contentid = '#' + DeCSModalTitlePrefix + identifier;
        if ($(this).is(':checked')) {
            $('#modal2').find(contentid).prop('checked', true);
            if (num == 0) {
                if ($('#modal2').find(titleid).hasClass('active') == false) {
                    $('#modal2').find(titleid).toggle('nav-link active');
                }
            }
            num++;
        } else {
            $('#modal2').find(titleid).hide();
            $('#modal2').find(contentid).hide();
        }
    });
}

function setLanguages(langArr) {
    var count = 0;
    $(document).find('.languageCheckbox').each(function () {
        var index = langArr.indexOf($(this).val());
        if (index > -1) {
            $(this).attr('checked', true);
        } else {
            $(this).attr('checked', false);
        }
        count++;
    });
}

function getLanguages() {
    var langs = [];
    var langObj = $(document).find('.languageCheckbox').each(function () {
        if ($(this).is(':checked')) {
            langs.push($(this).val());
        }
    });
    return langs;
}

function getSelectedDescriptors() {
    var DeCSModalTitlePrefix = 'opcao';
    var DeCSModalTitlePostfix = '-tab';
    var num = 0;
    var SelectedDescriptors = [];
    $('#modal2').find('.DeCSCheckBoxElement').each(function () {
        if (!($(this).find('input').first().is(':checked'))) {
            return;
        }
        var identifier = ($(this).attr('id')).substring(DeCSModalTitlePrefix.length);
        var tmp = identifier.split('-');
        var DescriptorNum = tmp[0];
        var DeCSLabelText = $(this).find('label').first().text();
        var DeCSArr = DeCSLabelText.split(', ');
        if (!(SelectedDescriptors[DescriptorNum])) {
            SelectedDescriptors[DescriptorNum] = [];
        }
        SelectedDescriptors[DescriptorNum].push(DeCSArr);
    });
    var SelectedDescriptors = SelectedDescriptors.filter(function (item) {
        return item !== null;
    });
    return SelectedDescriptors;
}