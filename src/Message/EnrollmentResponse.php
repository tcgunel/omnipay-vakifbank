<?php

namespace Omnipay\Vakifbank\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Vakifbank\Models\EnrollmentResponseModel;

class EnrollmentResponse extends AbstractResponse implements RedirectResponseInterface
{
    public function isSuccessful(): bool
    {
        return $this->getData()->Status === 'Y';
    }

    public function isRedirect(): bool
    {
        return $this->isSuccessful();
    }

    public function getMessage()
    {
        $message = '';

        if ($this->getData()->Status === 'E'){

            $message = '3D işlem sırasında bir hata oluştu. Kartınızın 3D işlemlere açık olduğunu ve limitinizin yeterli olduğunu kontrol ediniz.';

        }

        if ($this->getData()->Status !== 'Y'){

            $message = 'Kartınız 3D ödeme yöntemi programına dahil değil. Bankanıza başvurunuz.';

        }

        return $message;
    }

    public function getRedirectUrl()
    {
        /** @var EnrollmentResponseModel $data */
        $data = $this->getData();

        return $data->ACSUrl;
    }

    public function getRedirectMethod(): string
    {
        return 'POST';
    }

    public function getRedirectData(): array
    {
        /** @var EnrollmentResponseModel $data */
        $data = $this->getData();

        return [
            'PaReq'   => $data->PaReq,
            'TermUrl' => $data->TermUrl,
            'MD'      => $data->MD,
        ];
    }


    public function getRedirectResponse()
    {
        $response = parent::getRedirectResponse();

        $response->setContent(str_replace("<body", "<body style='color:#FFF'", $response->getContent()));

        $script = '<script>
			document.forms[0].style.display = "none";
	        document.getElementsByTagName("section")[0].style.display = "block";

			setTimeout(function() {
			  document.body.style.color = "auto";
			  document.forms[0].style.display = "block";
			  document.getElementsByTagName("section")[0].style.display = "none";
			}, 5000);
		</script>';

        $response->setContent(str_replace("</body>", "$script</body>", $response->getContent()));

        $response->setContent(str_replace("</body>", $this->redirectSpinner() . "</body>", $response->getContent()));

        return $response;
    }

    protected function redirectSpinner(): string
    {
        $css = '<style>
					section {
					  width: 174px;
					  margin: 0 auto;
					  padding: 20px;
					}

					.spinner {
					  animation: rotate 1.4s linear infinite;
					  -webkit-animation: rotate 1.4s linear infinite;
					  -moz-animation: rotate 1.4s linear infinite;
					  width:114px;
					  height:114px;
					  position: relative;
					}

					.spinner-dot {
					  width:214px;
					  height:214px;
					  position: relative;
					  top: 0;
					}


					@keyframes rotate {
					  to {
					    transform: rotate(360deg);
					  }
					}

					@-webkit-keyframes rotate {
					  to {
					    -webkit-transform: rotate(360deg);
					  }
					}

					@-moz-keyframes rotate {
					  to {
					    transform: rotate(360deg);
					  }
					}

					.path {
					  stroke-dasharray: 170;
					  stroke-dashoffset: 20;
					}
			</style>';

        $html = '<section>
		  <svg class="spinner" width="174px" height="174px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
		     <circle class="path" fill="transparent" stroke-width="2" cx="33" cy="33" r="30" stroke="url(#gradient)"/>
		       <linearGradient id="gradient">
		         <stop offset="50%" stop-color="#42d179" stop-opacity="1"/>
		         <stop offset="65%" stop-color="#42d179" stop-opacity=".5"/>
		         <stop offset="100%" stop-color="#42d179" stop-opacity="0"/>
		       </linearGradient>
		    </circle>
		     <svg class="spinner-dot dot" width="5px" height="5px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg" x="37" y="1.5">
		       <circle class="path" fill="#42d179" cx="33" cy="33" r="30"/>
		      </circle>
		    </svg>
		  </svg>
		</section>';

        return $css . $html;
    }

}
