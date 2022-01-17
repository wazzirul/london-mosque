<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;

class ContactUsController extends Controller
{
    //
    
    //////////////////////////////////////////////////////////////////////////////////////
    // About Us
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/contact-us/ContactMail",
     *     tags={"Contact Us"},
     *     summary="Contact Us Email response",
     *     description="Contact Us Email response",
     *     operationId="ContactMailContactUs",
     * 
     *   @SWG\Parameter(
     *     name="name",
     *     in="formData",
     *     description="Full Name ...",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="email",
     *     in="formData",
     *     description="Email Address ...",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="message",
     *     in="formData",
     *     description="Message ...",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="send",
     *     in="formData",
     *     description="(BUTTON SEND) ...",
     *     default=1,
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function ContactMail (){

        
        if(isset($_POST['send'])) {
  
        //   require 'vendor/autoload.php';
          
          $name = $_POST['name'];
          $email = $_POST['email'];
          $message = $_POST['message'];
        
          $mail = new PHPMailer;
          $mail->isSMTP();
          
        //   $mail->SMTPDebug = 4;
          $mail->Host = 'smtp.gmail.com';
          $mail->Port = 587;
          $mail->Username = 'londonmosquemailer@gmail.com'; // Email Yang Digunakan untuk Mengirim ----------------------- SETTING EMAIL TERLEBIH DULU
          $mail->Password = 'londonmailerpass'; // Password Email Yang Digunakan Untuk Mengirim ----- SETTING EMAIL TERLEBIH DULU
          $mail->SMTPSecure = 'tls';
          $mail->SMTPAuth = true;
          $mail->isHTML(true);
          $mail->addAddress('londonmosquemailer@gmail.com'); // Email Yang Penerima message.. Contoh = JagadTour@gmail.com
          $mail->setFrom('londonmosquemailer@gmail.com', 'Contact Us'); // Email Yang Mengirim message .. Contoh = JagadTour2@gmail.com
          $mail->Subject = 'Sender ' . $name;
          $mail->Body = '      
                   
                <body style="height: 100%; margin: 0; padding: 0; width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; background-color: #FAFAFA;">
                <!--*|IF:MC_PREVIEW_TEXT|*-->
                <!--[if !gte mso 9]>
                <!---->
                <span class="mcnPreviewText" style="font-size: 0px; line-height: 0px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; visibility: hidden; mso-hide: all; display: none;">Kiriman Email dari Pelanggan</span>
                <!--
                <![endif]-->
                <!--*|END:IF|*-->
                <center>
                  <table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; height: 100%; margin: 0; padding: 0; width: 100%; background-color: #FAFAFA;" bgcolor="#FAFAFA">
                    <tr>
                      <td align="center" valign="top" id="bodyCell" style="mso-line-height-rule: exactly; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; height: 100%; margin: 0; width: 100%; padding: 10px; border-top: 0;" width="100%" height="100%">
                        <!-- BEGIN TEMPLATE // -->
                        <!--[if (gte mso 9)|(IE)]>
                        <table align="center" border="0" cellspacing="0" cellpadding="0" width="600" style="width:600px;">
                          <tr>
                            <td align="center" valign="top" width="600" style="width:600px;">
                              <![endif]-->
                              <table border="0" cellpadding="0" cellspacing="0" width="100%" class="templateContainer" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border: 0; max-width: 600px;">
                                <tr>
                                  <td valign="top" id="templatePreheader" style="mso-line-height-rule: exactly; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; background-color: #FAFAFA; background-image: none; background-repeat: no-repeat; background-position: center; background-size: cover; border-top: 0; border-bottom: 0; padding-top: 9px; padding-bottom: 9px;" bgcolor="#FAFAFA" background="none">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnImageBlock" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; min-width: 100%;">
                                      <tbody class="mcnImageBlockOuter">
                                        <tr>
                                          <td valign="top" style="mso-line-height-rule: exactly; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding: 9px;" class="mcnImageBlockInner">
                                            <table align="left" width="100%" border="0" cellpadding="0" cellspacing="0" class="mcnImageContentContainer" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; min-width: 100%;">
                                              <tbody>
                                                <tr>
                                                  <td class="mcnImageContent" valign="top" style="mso-line-height-rule: exactly; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-right: 9px; padding-left: 9px; padding-top: 0; padding-bottom: 0; text-align: center;" align="center">
                                                    <img align="center" alt="London Mosque" src="https://londonmosqueapi.illiyin.co/assets/img/LogoLondon-Mosque.png" width="175" style="border: 0; height: auto; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; max-width: 175px; padding-bottom: 0; vertical-align: bottom; display: inline;" class="mcnImage">
                                                    </td>
                                                  </tr>
                                                </tbody>
                                              </table>
                                            </td>
                                          </tr>
                                        </tbody>
                                      </table>
                                      <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnDividerBlock" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; min-width: 100%; background-color: #FFB13D; table-layout: fixed;" bgcolor="#FFB13D">
                                        <tbody class="mcnDividerBlockOuter">
                                          <tr>
                                            <td class="mcnDividerBlockInner" style="mso-line-height-rule: exactly; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; min-width: 100%; padding: 18px;">
                                              <table class="mcnDividerContent" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; min-width: 100%;">
                                                <tbody>
                                                  <tr>
                                                    <td style="mso-line-height-rule: exactly; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">
                                                      <span></span>
                                                    </td>
                                                  </tr>
                                                </tbody>
                                              </table>
                                              <!-- <td class="mcnDividerBlockInner" style="padding: 18px;"><hr class="mcnDividerContent" style="border-bottom-color:none; border-left-color:none; border-right-color:none; border-bottom-width:0; border-left-width:0; border-right-width:0; margin-top:0; margin-right:0; margin-bottom:0; margin-left:0;" /> -->
                                            </td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td valign="top" id="templateHeader" style="mso-line-height-rule: exactly; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; background-color: #FFFFFF; background-image: none; background-repeat: no-repeat; background-position: center; background-size: cover; border-top: 0; border-bottom: 0; padding-top: 9px; padding-bottom: 0;" bgcolor="#FFFFFF" background="none">
                                      <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; min-width: 100%;">
                                        <tbody class="mcnTextBlockOuter">
                                          <tr>
                                            <td valign="top" class="mcnTextBlockInner" style="mso-line-height-rule: exactly; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 9px;">
                                              <!--[if mso]>
                                              <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;">
                                                <tr>
                                                  <![endif]-->
                                                  <!--[if mso]>
                                                  <td valign="top" width="600" style="width:600px;">
                                                    <![endif]-->
                                                    <table align="left" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; max-width: 100%; min-width: 100%;" width="100%" class="mcnTextContentContainer">
                                                      <tbody>
                                                        <tr>
                                                          <td valign="top" class="mcnTextContent" style="mso-line-height-rule: exactly; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; word-break: break-word; color: #202020; font-family: Helvetica; font-size: 16px; line-height: 150%; text-align: left; padding-top: 0; padding-right: 18px; padding-bottom: 9px; padding-left: 18px;" align="left">
                                                            <h1 style="display: block; margin: 0; padding: 0; color: #FFB13D; font-family: Helvetica; font-size: 16px; font-style: normal; font-weight: bold; line-height: 125%; letter-spacing: normal; text-align: center;">Contact Us</h1>
                                                            <p>
                                                              <b>Email Sender</b>
                                                              <br/>' . $email . '
                                                            </p>
                                                            <p>
                                                              <b>Message</b>
                                                            </p>
                                                            <p>' . $message . '</p>
                                                          </td>
                                                        </tr>
                                                      </tbody>
                                                    </table>
                                                    <!--[if mso]>
                                                  </td>
                                                  <![endif]-->
                                                  <!--[if mso]>
                                                </tr>
                                              </table>
                                              <![endif]-->
                                            </td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td valign="top" id="templateBody" style="mso-line-height-rule: exactly; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; background-color: #FFFFFF; background-image: none; background-repeat: no-repeat; background-position: center; background-size: cover; border-top: 0; border-bottom: 2px solid #EAEAEA; padding-top: 0; padding-bottom: 9px;" bgcolor="#FFFFFF" background="none"></td>
                                  </tr>
                                  <tr>
                                    <td valign="top" id="templateFooter" style="mso-line-height-rule: exactly; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; background-color: #FAFAFA; background-image: none; background-repeat: no-repeat; background-position: center; background-size: cover; border-top: 0; border-bottom: 0; padding-top: 9px; padding-bottom: 9px;" bgcolor="#FAFAFA" background="none">
                                      <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnDividerBlock" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; min-width: 100%; background-color: #005E6D; table-layout: fixed;" bgcolor="#29BAFD">
                                        <tbody class="mcnDividerBlockOuter">
                                          <tr>
                                            <td class="mcnDividerBlockInner" style="mso-line-height-rule: exactly; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; min-width: 100%; padding: 10px 18px 25px;">
                                              <table class="mcnDividerContent" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; min-width: 100%; border-top: 2px none #EEEEEE;">
                                                <tbody>
                                                  <tr>
                                                    <td style="mso-line-height-rule: exactly; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">
                                                      <span></span>
                                                    </td>
                                                  </tr>
                                                </tbody>
                                              </table>
                                              <!-- <td class="mcnDividerBlockInner" style="padding: 18px;"><hr class="mcnDividerContent" style="border-bottom-color:none; border-left-color:none; border-right-color:none; border-bottom-width:0; border-left-width:0; border-right-width:0; margin-top:0; margin-right:0; margin-bottom:0; margin-left:0;" /> -->
                                            </td>
                                          </tr>
                                        </tbody>
                                      </table>
                                      <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; min-width: 100%;">
                                        <tbody class="mcnTextBlockOuter">
                                          <tr>
                                            <td valign="top" class="mcnTextBlockInner" style="mso-line-height-rule: exactly; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 9px;">
                                              <!--[if mso]>
                                              <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;">
                                                <tr>
                                                  <![endif]-->
                                                  <!--[if mso]>
                                                  <td valign="top" width="600" style="width:600px;">
                                                    <![endif]-->
                                                    <table align="left" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; max-width: 100%; min-width: 100%;" width="100%" class="mcnTextContentContainer">
                                                      <tbody>
                                                        <tr>
                                                          <td valign="top" class="mcnTextContent" style="mso-line-height-rule: exactly; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; word-break: break-word; color: #656565; font-family: Helvetica; font-size: 12px; line-height: 150%; text-align: center; padding-top: 0; padding-right: 18px; padding-bottom: 9px; padding-left: 18px;" align="center">
                                                            <em>Copyright © London Mosques, All rights reserved.</em>
                                                        </td>
                                                        </tr>
                                                       </tbody>
                                                    </table>
                                                   <!--[if mso]>
                                                   </td>
                                                   <![endif]-->
                                                   <!--[if mso]>
                                                </tr>
                                               </table>
                                               <![endif]-->
                                                      </td>
                                                    </tr>
                                                  </tbody>
                                                </table>
                                              </td>
                                            </tr>
                                          </table>
                                          <!--[if (gte mso 9)|(IE)]>
                                        </td>
                                      </tr>
                                    </table>
                                    <![endif]-->
                                    <!-- // END TEMPLATE -->
                                  </td>
                                </tr>
                              </table>
                            </center>
                          </body>
                ';
          $mail->send();
        } else {
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['message'] = 'Please Enter Valid Data';

            return response($res, 200);
        } 
        $res['status_code'] = 200;
        $res['success'] = true;
        $res['message'] = 'Success Sending Email Contact Us';

        return response($res, 200);     
      }
}
