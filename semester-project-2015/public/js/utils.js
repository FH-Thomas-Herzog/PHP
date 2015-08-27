var setAction = function (actionId) {
        $('#actionId').val(actionId);
    }
    ,
    destroyValidation = function (formId) {
        $("#" + formId).validator('destroy');
    }
    ,
    submitCancelWithValidation = function (formId, params) {
        destroyValidation(formId);
        setAction(actionId);
        $("#" + formId).submit();
    }
    ,
    ajaxPostRequest = function (htmlTargetId, relUrl, params, successFunc, errorFunc) {
        $("#ajaxLoaderImage").show();
        $.ajax({
            async: true,
            type: "POST",
            url: relUrl,
            data: ("ajax=true" + (params ? ("&" + params) : "")),
            complete: function (xhr, status) {
                try {
                    // reset fromer set message
                    $("#message").text("");
                    $("#additonalMessage").text("");
                    hideElement("messageRow");

                    var resultObj = JSON.parse(xhr.responseText);

                    if ((resultObj.message) || (resultObj.additionalMessage)) {
                        showElement("messageRow");
                        $("#messageAlert").attr("class", "alert alert-" + resultObj.messageType + " alert-dismissible");
                    }
                    if (resultObj.message) {
                        $("#message").text(resultObj.message);
                    }
                    if (resultObj.additionalMessage) {
                        $("#additionalMessage").text(resultObj.additionalMessage);
                    }
                    if ((resultObj.error === true) && (successFunc)) {
                        successFunc(resultObj);
                    }
                    if ((resultObj.error === false) && (errorFunc)) {
                        errorFunc(resultObj);
                    }
                    if (resultObj.redirectUrl) {
                        toUrl(resultObj.redirectUrl);
                        return;
                    }
                    if ((resultObj.html) && (htmlTargetId)) {
                        $("#" + htmlTargetId).html(resultObj.html);
                        if (successFunc) {
                            successFunc(resultObj);
                        }
                    }
                } catch (err) {
                    if (console) {
                        console.log(err);
                    }
                }
                $("#ajaxLoaderImage").hide();
            }
        });
        // prevent submit of form
        return false;
    },
    ajaxPostRequestSubmitForm = function (formId, htmlTargetId, relUrl, params, successFunc, errorFunc) {
        var form = $('#' + formId);
        if (!form) {
            throw "Form with id '" + formId + "' does not exist";
        }
        ajaxPostRequest(htmlTargetId, relUrl, form.serialize() + ((params) ? ("&" + params) : ""), successFunc, errorFunc);
    },
    hideElement = function (id) {
        var element = $("#" + id);
        if (!element) {
            throw "Can not find element with id '" + id + "' which should be hided";
        }
        element.hide();
    },
    showElement = function (id) {
        var element = $("#" + id);
        if (!element) {
            throw "Can not find element with id '" + id + "' which should be showed";
        }
        element.show();
    },
    toUrl = function (url) {
        document.location.href = url;
    };
;