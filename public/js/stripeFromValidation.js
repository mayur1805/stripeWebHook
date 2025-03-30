$(document).ready(function(){
  $("#paymentInitiate").validate({
          rules: {
              amount: {
                  required: true,
                  min: 1
              },
          },
          messages: {
              amount: {
                  required: "Amount field is required",
                  min: "The amount must be greater than 0."
              },
          },
          errorPlacement: function (error, element) {
            error.addClass("text-danger"); // Add Bootstrap class for red color
            error.insertAfter(element);
        },
        highlight: function (element) {
            $(element).addClass("error-border");
        },
        unhighlight: function (element) {
            $(element).removeClass("error-border");
        },
          submitHandler: function(form) {
            alert("Form submitted successfully!");
            form.submit();
        }
      });
});