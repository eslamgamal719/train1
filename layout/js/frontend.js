$(function () {


		'use strict';
    
		/* Switch Between Login And Signup */

		$('.login-page h2 span').click(function(){
			$(this).addClass('selected').siblings().removeClass('selected');
			$('.login-page form').hide();
			$('.' + $(this).data('class')).show();

		});





		//Trigger The SelectBoxIt

		$("select").selectBoxIt({

			

		});



		$('[placeholder]').focus(function(){       //note [placeholder]  == input

			$(this).attr('data-text', $(this).attr('placeholder'));

			$(this).attr('placeholder', '');
		
			}).blur(function(){

				$(this).attr('placeholder', $(this).attr('data-text'));

			});


			//Add Astrisk On Required Field
		$('input').each(function() {

			if($(this).attr('required') === 'required') {
				
				$(this).after('<span class="asterisk">*</span');
			}

		});	




		//confirmation Message On Button Delete
		$('.confirm').click(function(){

			return confirm('Are You Sure?');
		});




		$('.live').keyup(function() {
			$($(this).data('class')).text($(this).val());
		});





});