<div id="content_user_header">Register</div>
[+wlpe.message+]
<div id="profile_top"></div>
<div id="profile_title">Neues user registrierung</div>
<div id="profile_data">
	<form enctype="multipart/form-data" id="wlpeUserRegisterForm" action="/[~[*id*]~]" method="POST">
<input id="wlpeUserRegisterDob" type="hidden" name="dob" value="0" />
    <div>
        <label class="required" for="wlpeUserRegisterEmail">Email
			<input id="wlpeUserRegisterEmail" type="text" name="email" value="[+post.email+]" />
			</label>

			<label for="wlpeUserRegisterUserName"><span class="required">*</span> Desired User Name
			<input id="wlpeUserRegisterUserName" type="text" name="username" value="[+post.username+]" />
			</label>

			<label for="wlpeUserRegisterFullName"><span class="required">*</span> Full Name
			<input id="wlpeUserRegisterFullName" type="text" name="fullname" value="[+post.fullname+]" />
			</label><br />

			<label for="wlpeUserRegisterPassword"><span class="required">*</span> Password
			<input id="wlpeUserRegisterPassword" type="password" name="password" value="[+post.password+]" />
			</label><br />

			<label for="wlpeUserRegisterPasswordConfirm"><span class="required">*</span> Password (confirm)
			<input id="wlpeUserRegisterPasswordConfirm" type="password" name="passwordconfirm" value="[+post.passwordconfirm+]" />
			</label><br />

			<label for="wlpeUserRegisterPhone">Phone number
			<input id="wlpeUserRegisterPhone" type="text" name="phone" />
			</label><br />

			<label for="wlpeUserRegisterMobile">Mobile number
			<input id="wlpeUserRegisterMobile" type="text" name="mobilephone" value="[+post.mobilephone+]" />
			</label><br />

			<label for="wlpeUserRegisterFax">Fax number
			<input id="wlpeUserRegisterFax" type="text" name="fax" value="[+post.fax+]" />
			</label><br />

			<label for="wlpeUserRegisterState">State
			<input id="wlpeUserRegisterState" type="text" name="state" value="[+post.state+]" />
			</label><br />

			<label for="wlpeUserRegisterZip">Zip Code
			<input id="wlpeUserRegisterZip" type="text" name="zip" value="[+post.zip+]" />
			</label><br />

    </div>

    [+form.gender+]<br />

    [+form.user_type+]<br />
     <p id="wlpeTermsOfServiceLabel">Terms of Service/Privacy Policy</p>

			<label for="wlpeTosCheckbox" id="wlpeTosCheckboxLabel"><span class="required">*</span>I accept the <a href="[~21~]">Terms of Service</a>
				<input type="checkbox" id="wlpeTosCheckbox" name="tos" />
			</label><br />
		<fieldset id="wlpeUserProfileButtons">
			<button type="submit" id="button_profile_ok" name="service" value="register">Save</button>
			<button type="reset" id="button_profile_clear">Done</button>

		</fieldset>

	</form>
</div>