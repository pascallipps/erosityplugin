/* Erosity Public JavaScript */

jQuery(document).ready(function($) {
    'use strict';
    
    // Tab navigation
    $('.erosity-tabs li a').on('click', function(e) {
        e.preventDefault();
        var target = $(this).attr('href');
        
        $('.erosity-tabs li').removeClass('active');
        $(this).parent().addClass('active');
        
        $('.erosity-tab-content').removeClass('active');
        $(target).addClass('active');
    });
    
    // Multi-step form navigation
    $('.next-step').on('click', function() {
        var currentStep = $(this).closest('.form-step');
        var nextStep = currentStep.next('.form-step');
        
        if (nextStep.length) {
            currentStep.removeClass('active');
            nextStep.addClass('active');
            
            var stepNum = nextStep.data('step');
            updateStepIndicator(stepNum);
            
            // Save current step data
            saveStepData(currentStep);
        }
    });
    
    $('.prev-step').on('click', function() {
        var currentStep = $(this).closest('.form-step');
        var prevStep = currentStep.prev('.form-step');
        
        if (prevStep.length) {
            currentStep.removeClass('active');
            prevStep.addClass('active');
            
            var stepNum = prevStep.data('step');
            updateStepIndicator(stepNum);
        }
    });
    
    function updateStepIndicator(stepNum) {
        $('.erosity-form-steps .step').removeClass('active');
        $('.erosity-form-steps .step[data-step="' + stepNum + '"]').addClass('active');
    }
    
    function saveStepData(stepElement) {
        var formData = stepElement.find('input, textarea, select').serialize();
        var stepNum = stepElement.data('step');
        var propertyId = $('#property_id').val();
        
        $.ajax({
            url: erosityData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'erosity_save_property_step',
                nonce: erosityData.nonce,
                step: stepNum,
                property_id: propertyId,
                data: formData
            },
            success: function(response) {
                if (response.success) {
                    $('#property_id').val(response.data.property_id);
                }
            }
        });
    }
    
    // Login form
    $('#erosity-login-form').on('submit', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var submitBtn = form.find('button[type="submit"]');
        var messageDiv = form.find('.erosity-message');
        
        submitBtn.prop('disabled', true);
        messageDiv.html('');
        
        $.ajax({
            url: erosityData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'erosity_login',
                nonce: erosityData.nonce,
                username: form.find('#username').val(),
                password: form.find('#password').val(),
                remember: form.find('input[name="remember"]').is(':checked') ? 1 : 0
            },
            success: function(response) {
                if (response.success) {
                    messageDiv.html('<div class="success">' + response.data.message + '</div>');
                    if (response.data.redirect) {
                        window.location.href = response.data.redirect;
                    }
                } else {
                    messageDiv.html('<div class="error">' + response.data.message + '</div>');
                }
                submitBtn.prop('disabled', false);
            },
            error: function() {
                messageDiv.html('<div class="error">' + erosityData.strings.error + '</div>');
                submitBtn.prop('disabled', false);
            }
        });
    });
    
    // Register form
    $('#erosity-register-form').on('submit', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var submitBtn = form.find('button[type="submit"]');
        var messageDiv = form.find('.erosity-message');
        
        submitBtn.prop('disabled', true);
        messageDiv.html('');
        
        $.ajax({
            url: erosityData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'erosity_register',
                nonce: erosityData.nonce,
                username: form.find('#reg_username').val(),
                email: form.find('#reg_email').val(),
                password: form.find('#reg_password').val(),
                password_confirm: form.find('#reg_password_confirm').val()
            },
            success: function(response) {
                if (response.success) {
                    messageDiv.html('<div class="success">' + response.data.message + '</div>');
                    if (response.data.redirect) {
                        window.location.href = response.data.redirect;
                    }
                } else {
                    messageDiv.html('<div class="error">' + response.data.message + '</div>');
                }
                submitBtn.prop('disabled', false);
            },
            error: function() {
                messageDiv.html('<div class="error">' + erosityData.strings.error + '</div>');
                submitBtn.prop('disabled', false);
            }
        });
    });
    
    // Filter form
    $('#erosity-filter-form').on('submit', function(e) {
        e.preventDefault();
        
        var form = $(this);
        
        $.ajax({
            url: erosityData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'erosity_filter_properties',
                nonce: erosityData.nonce,
                search: form.find('#search').val(),
                location: form.find('#location').val(),
                radius: form.find('#radius').val(),
                booking_type: form.find('#booking_type').val(),
                min_price: form.find('#min_price').val(),
                max_price: form.find('#max_price').val()
            },
            success: function(response) {
                if (response.success) {
                    $('#erosity-properties-grid').html(response.data.html);
                }
            }
        });
    });
});
