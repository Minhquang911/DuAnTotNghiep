     <!-- Login Modal -->
     <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
         <div class="modal-dialog">
             <div class="modal-content">
                 <div class="modal-body">
                     <div class="close-btn">
                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                     </div>
                     <div class="identityBox">
                         <div class="form-wrapper">
                             <h1 id="loginModalLabel">welcome back!</h1>
                             <input class="inputField" type="email" name="email" placeholder="Email Address">
                             <input class="inputField" type="password" name="password" placeholder="Enter Password">
                             <div class="input-check remember-me">
                                 <div class="checkbox-wrapper">
                                     <input type="checkbox" class="form-check-input" name="save-for-next"
                                         id="saveForNext">
                                     <label for="saveForNext">Remember me</label>
                                 </div>
                                 <div class="text"> <a href="index-2.html">Forgot Your password?</a> </div>
                             </div>
                             <div class="loginBtn">
                                 <a href="index-2.html" class="theme-btn rounded-0"> Log in </a>
                             </div>
                             <div class="orting-badge">
                                 Or
                             </div>
                             <div>
                                 <a class="another-option" href="https://www.google.com/">
                                     <img src="{{ asset('client/img/google.png') }}" alt="google">
                                     Continue With Google
                                 </a>
                             </div>
                             <div>
                                 <a class="another-option another-option-two" href="https://www.facebook.com/">
                                     <img src="{{ asset('client/img/facebook.png') }}" alt="google">
                                     Continue With Facebook
                                 </a>
                             </div>

                             <div class="form-check-3 d-flex align-items-center from-customradio-2 mt-3">
                                 <input class="form-check-input" type="radio" name="flexRadioDefault">
                                 <label class="form-check-label">
                                     I Accept Your Terms & Conditions
                                 </label>
                             </div>
                         </div>

                         <div class="banner">
                             <button type="button" class="rounded-0 login-btn" data-bs-toggle="modal"
                                 data-bs-target="#loginModal">Log in</button>
                             <button type="button" class="theme-btn rounded-0 register-btn" data-bs-toggle="modal"
                                 data-bs-target="#registrationModal">Create
                                 Account</button>
                             <div class="loginBg">
                                 <img src="{{ asset('client/img/signUpbg.jpg') }}" alt="signUpBg">
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>

     <!-- Registration Modal -->
     <div class="modal fade" id="registrationModal" tabindex="-1" aria-labelledby="registrationModalLabel"
         aria-hidden="true">
         <div class="modal-dialog">
             <div class="modal-content">
                 <div class="modal-body">
                     <div class="close-btn">
                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                     </div>
                     <div class="identityBox">
                         <div class="form-wrapper">
                             <h1 id="registrationModalLabel">Create account!</h1>
                             <input class="inputField" type="text" name="name" id="name"
                                 placeholder="User Name">
                             <input class="inputField" type="email" name="email" placeholder="Email Address">
                             <input class="inputField" type="password" name="password" placeholder="Enter Password">
                             <input class="inputField" type="password" name="password"
                                 placeholder="Enter Confirm Password">
                             <div class="input-check remember-me">
                                 <div class="checkbox-wrapper">
                                     <input type="checkbox" class="form-check-input" name="save-for-next"
                                         id="rememberMe">
                                     <label for="rememberMe">Remember me</label>
                                 </div>
                                 <div class="text"> <a href="index-2.html">Forgot Your password?</a> </div>
                             </div>
                             <div class="loginBtn">
                                 <a href="index-2.html" class="theme-btn rounded-0"> Log in </a>
                             </div>
                             <div class="orting-badge">
                                 Or
                             </div>
                             <div>
                                 <a class="another-option" href="https://www.google.com/">
                                     <img src="{{ asset('client/img/google.png') }}" alt="google">
                                     Continue With Google
                                 </a>
                             </div>
                             <div>
                                 <a class="another-option another-option-two" href="https://www.facebook.com/">
                                     <img src="{{ asset('client/img/facebook.png') }}" alt="google">
                                     Continue With Facebook
                                 </a>
                             </div>
                             <div class="form-check-3 d-flex align-items-center from-customradio-2 mt-3">
                                 <input class="form-check-input" type="radio" name="flexRadioDefault">
                                 <label class="form-check-label">
                                     I Accept Your Terms & Conditions
                                 </label>
                             </div>
                         </div>

                         <div class="banner">
                             <button type="button" class="rounded-0 login-btn" data-bs-toggle="modal"
                                 data-bs-target="#loginModal">Log in</button>
                             <button type="button" class="theme-btn rounded-0 register-btn" data-bs-toggle="modal"
                                 data-bs-target="#registrationModal">Create
                                 Account</button>
                             <div class="signUpBg">
                                 <img src="{{ asset('client/img/registrationbg.jpg') }}" alt="signUpBg">
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
