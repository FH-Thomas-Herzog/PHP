var
    setFocusOnLastMessage = function () {
        var item = $('.last-message');
        if (item) {
            item.focus();
        }
    }
    ,
    invertIntegerFlag = function (flag) {
        return (flag === 0) ? 1 : (flag === 1) ? 0 : null;
    }
    ,
    handleAjaxAlertMessage = function (resultObj) {
        // reset fromer set message
        $("#message").text("");
        $("#additionalMessage").text("");

        hideElement("messageRow");

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
                    var resultObj = JSON.parse(xhr.responseText);

                    handleAjaxAlertMessage(resultObj);

                    if ((resultObj.error === true) && (successFunc)) {
                        successFunc(resultObj);
                    }
                    if ((resultObj.html) && (htmlTargetId)) {
                        $("#" + htmlTargetId).empty();
                        $("#" + htmlTargetId).html(resultObj.html);
                    }
                    if (resultObj.redirectUrl) {
                        var delay = (resultObj.delay) ? resultObj.delay : 0;
                        setTimeout(function () {
                            toUrl(resultObj.redirectUrl);
                        }, delay);
                    } else {
                        if ((resultObj.error === false) && (successFunc)) {
                            successFunc(resultObj, xhr, status);
                        }
                        if ((resultObj.error === true) && (errorFunc)) {
                            errorFunc(resultObj, xhr, status);
                        }
                    }
                } catch (err) {
                    if (console) {
                        console.log(err);
                        console.log(xhr.responseText);
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