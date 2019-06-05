$(function () {
    var companyDrop = $('#company-select'),
            publisherDrop = $('#publisher-select'),
            segmentDrop = $('#segment-select'),
            titlesContainer = $('.title-container'),
            descriptionContainer = $('.description-container'),
            urlContainer = $('.url-container'),
            loadingImg = $('.loading-img.one'),
            loadingImg2 = $('.loading-img.two'),
            loadingImg3 = $('.loading-img.three'),
            textSuccess = $('.message-status1'),
            textSuccess2 = $('.message-status2'),
            textSuccess3 = $('.message-status3'),
            hasXmlLayout = $('.has-xml-layout'),
            saveLayout = $('#save-xml-layout'),
            xmlLayout = $('#xml-layout'),
            hasPhone = $('.has-phone'),
            savePhone = $('#save-phone'),
            phone = $('#phone'),
            id_publisher = '',
            id_segment = '',
            xmlRoot = $('#xml-root'),
            xmlExtraFields = $('#extra-fields'),
            saveXmlOptions = $('#save-xml-fields');

    companyDrop.on('change', function () {
        var option = $(this).find('option:selected').val();
        if (option && option != '') {
            publisherDrop.attr('disabled', true);
            $.getJSON('get-publishers', {id_company: option}, function (data) {
                publisherDrop.html(data.publishers);
                if (data.publishers && data.publishers != '') {
                    publisherDrop.attr('disabled', false);
                }
            });
        }
    });

    publisherDrop.on('change', function () {
        var option = $(this).find('option:selected').val();
        id_publisher = option;

        if (option && option != '') {
            titlesContainer.html('');
            descriptionContainer.html('');
            urlContainer.html('');

            xmlLayout.val('');
            phone.val('');
            textSuccess.html('');
            textSuccess2.html('');
            hasXmlLayout.addClass('hide');
            hasPhone.addClass('hide');

            segmentDrop.attr('disabled', true);
            $.get('get-segments', {id_publisher: option}, function (data) {
                segmentDrop.html(data.segments);
                if (data.segments && data.segments != '') {
                    segmentDrop.attr('disabled', false);
                }
            });

            $.get('get-xml-options', {id_publisher: option}, function (data) {
                xmlRoot.val(data.xml_root);
                xmlExtraFields.val(data.xml_extra_fields);
            });
        }
    });

    segmentDrop.on('change', function () {
        var option = $(this).find('option:selected').val();
        id_segment = option;

        if (option && option != '') {
            textSuccess.html('');
            textSuccess2.html('');

            $.get('get-segment-relations', {id_publisher: id_publisher, id_segment: id_segment}, function (data) {
                titlesContainer.html(data.titles);
                descriptionContainer.html(data.descriptions);
                urlContainer.html(data.urls);
            });

            xmlLayout.val('');

            $.get('get-xml-layout', {id_publisher: id_publisher, id_segment: id_segment}, function (data) {
                if (data.xml_layout) {
                    xmlLayout.val(data.xml_layout);
                    hasXmlLayout.removeClass('hide');
                } else {
                    xmlLayout.val('');
                }
            });

            phone.val('');
            $.get('get-phone', {id_publisher: id_publisher, id_segment: id_segment}, function (data) {
                if (data.phone) {
                    phone.val(data.phone);
                    hasPhone.removeClass('hide');
                } else {
                    phone.val('');
                }
            });
        }
    });

    xmlLayout.keyup(function () {
        var xml = $(this).val();

        if (xml) {
            hasXmlLayout.removeClass('hide');
        } else {
            hasXmlLayout.addClass('hide');
        }
    });

    saveLayout.on('click', function (event) {
        event.preventDefault();
        textSuccess.html('');

        if (!id_publisher && !id_segment) {
            alert('Ups! You need to select a publisher and segment.');
            return false;
        }
        if (!xmlLayout.val()) {
            alert('Ups! xml layout is required, please insert an XML layout.');
            return false;
        }
        var postFields = {
            id_publisher: id_publisher,
            id_segment: id_segment,
            xml_layout: xmlLayout.val()
        };

        loadingImg.removeClass('hide');
//        console.log('');
        $.post('save-xml-layout', postFields, function (data) {
            if (data.status === 'OK') {
                textSuccess.html('<strong>&nbsp;&nbsp;XML Job layout was saved successfully!</strong>')
            }
            saveLayout.removeAttr('disabled');
            loadingImg.addClass('hide');
        });

    });

    phone.keyup(function () {
        var phoneVal = $(this).val();

        if (phoneVal) {
            hasPhone.removeClass('hide');
        } else {
            hasPhone.addClass('hide');
        }
    });

    savePhone.on('click', function (event) {
        event.preventDefault();
        textSuccess2.html('');

        if (!id_publisher && !id_segment) {
            alert('Ups! You need to select a publisher and segment.');
            return false;
        }

        if (!phone.val()) {
            alert('Ups! phone is required, please insert a phone.');
            return false;
        }

        var postFields = {
            id_publisher: id_publisher,
            id_segment: id_segment,
            phone: phone.val()
        };

        loadingImg2.removeClass('hide');

        $.post('save-phone', postFields, function (data) {
            if (data.status == 'OK') {
                textSuccess2.html('<strong>&nbsp;&nbsp;Phone was saved successfully!</strong>')
            }
            savePhone.removeAttr('disabled');
            loadingImg2.addClass('hide');
        });

    });

    saveXmlOptions.on('click', function (event) {
        event.preventDefault();

        textSuccess3.html('');

        if (!id_publisher && !id_segment) {
            alert('Ups! You need to select a publisher and segment.');
            return false;
        }

        if (!xmlRoot.val()) {
            alert('Ups! xml root is required, please insert an XML layout.');
            return false;
        }


        var postFields = {
            id_publisher: id_publisher,
            id_segment: id_segment,
            xml_root: xmlRoot.val(),
            xml_extra_fields: xmlExtraFields.val()
        };

        loadingImg3.removeClass('hide');

        $.post('save-xml-options', postFields, function (data) {
            if (data.status == 'OK') {
                textSuccess3.html('<strong>&nbsp;&nbsp;XML options was saved successfully!</strong>');
            }
            saveXmlOptions.removeAttr('disabled');
            loadingImg3.addClass('hide');
        });
    });

});