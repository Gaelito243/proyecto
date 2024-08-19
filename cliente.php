<?php
session_start();
// Verificar si el usuario está autenticado y es cliente
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol_usuario'] != 'cliente') {
    header("Location: acceso.php");
    exit();
}
?>
<!DOCTYPE html><html style="font-size: 16px;"><head>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta charset="utf-8">
      <meta name="keywords" content="About Yoga Studio, Amazing, curious, healthy and happy teachers">
      <meta name="description" content="Descubre la paz interior y mejora tu salud con nuestros cursos de yoga en Velvet's Studio.">
      <meta name="page_type" content="np-template-header-footer-from-plugin">
      <title>Velvet's Studio</title>
      <link rel="stylesheet" href="../proyecto/estilo.css" media="screen">
      <script type="text/javascript" src="https://static.nicepage.com/shared/assets/jquery-1.9.1.min.js" defer></script>
  <script type="text/javascript" src="https://capp.nicepage.com/99f3030225e908200f7b9c08c9b47d686b3a81a6/nicepage.js" defer></script>
      <meta name="generator" content="Nicepage 3.5.1, nicepage.com">
      <link id="u-theme-google-font" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Playfair+Display:400,400i,700,700i,900,900i|Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i">
      <link id="u-page-google-font" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i|Montserrat:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i|Playfair+Display:400,400i,700,700i,900,900i|Playfair+Display:400,400i,700,700i,900,900i">
      <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
      <script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "Organization",
  "name": "Nombre de la Organización",
  "url": "https://website218957.nicepage.io/Page-15.html",
  "logo": "https://capp.nicepage.com/99f3030225e908200f7b9c08c9b47d686b3a81a6/images/default-logo.png"
}
</script>
      <meta property="og:title" content="Page 15">
      <meta property="og:type" content="website">
      <meta name="theme-color" content="#d4d9dc">
      <link rel="canonical" href="https://website218957.nicepage.io/Page-15.html">
      <meta property="og:url" content="https://website218957.nicepage.io/Page-15.html">
    
    </head>
    <body class="u-body">
    <header>
    <?php
include("navbar.php");
?>
    </header>
      
      <section class="u-clearfix u-image u-section-2" id="menu">
        <div class="u-clearfix u-sheet u-sheet-1">
          <div class="u-container-style u-group u-white u-group-1">
            <div class="u-container-layout u-valign-middle u-container-layout-1">
              <h3 class="u-custom-font u-font-raleway u-text u-text-1">Estetica</h3>
            </div>
          </div>
          <div class="u-black u-container-style u-group u-group-2">
            <div class="u-container-layout u-valign-middle u-container-layout-2">
              <h1 class="u-custom-font u-font-montserrat u-text u-text-2">Velvet's Studio</h1>
            </div>
          </div>
          <div class="u-container-style u-group u-group-3">
            <div class="u-container-layout u-container-layout-3">
              <p class="u-text u-text-body-alt-color u-text-3">RELAJACION Y RENOVACION</a>
              </p>
            </div>
          </div>
        </div>
      </section>
  <div class="separador"></div>
      <section class="u-align-center u-clearfix u-palette-3-light-3 u-section-3" id="servicios">
        <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
          <h2 class="u-align-left u-text u-text-palette-4-base u-text-1"> <span style="font-weight: 700;"> Ofrecemos servicios esteticos de todo tipo </span>
            <br/>
          </h2>
          <div class="u-clearfix u-expanded-width u-layout-wrap u-layout-wrap-1">
            <div class="u-layout">
              <div class="u-layout-row">
                <div class="u-container-style u-layout-cell u-size-38 u-layout-cell-1">
                  <div class="u-container-layout u-container-layout-1">
                    <div class="u-palette-4-base u-shape u-shape-circle u-shape-1"></div>
                    <img alt class="u-align-left u-image u-image-default u-image-1" src="../proyecto/imagenes/estudio.jpg">
                  </div>
                </div>
                <div class="u-align-left u-container-style u-layout-cell u-right-cell u-shape-rectangle u-size-22 u-layout-cell-2">
                  <div class="u-container-layout u-valign-top-sm u-container-layout-2">
                    <div class="u-align-left u-container-style u-group u-palette-3-base u-group-1">
                      <div class="u-container-layout u-valign-middle u-container-layout-3">
                        <h2 class="u-text u-text-palette-4-base u-text-2">Una experiencia unica y personalizada para lucir y sentirse espectacular</h2>
                      </div>
                    </div>
                    <p class="u-text u-text-3">Nos especializamos en ofrecer un buen servicio de calidad, para todas y todos nuestros clientes, teniendo siempre un ambiente cálido y tranquilo</p>
            
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="u-align-center u-clearfix u-image u-shading u-section-4" id="">
        <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
          <h2 class="u-text u-text-palette-4-base u-text-1">Servicios Esteticos</h2>
          <div class="u-border-2 u-border-palette-4-base u-line u-line-horizontal u-line-1"></div>
          <p class="u-text u-text-palette-4-base u-text-2"></p>
          <div class="u-expanded-width-md u-expanded-width-sm u-expanded-width-xs u-list u-repeater u-list-1">
            <div class="u-align-center u-container-style u-list-item u-repeater-item u-white u-list-item-1">
              <div class="u-container-layout u-similar-container u-valign-middle u-container-layout-1">
                <div alt class="u-image u-image-circle u-image-1"></div>
                <h3 class="u-custom-font u-text u-text-font u-text-palette-4-base u-text-3">Cuidado Capilar</h3>
                <p class="u-text u-text-palette-5-dark-1 u-text-4">$800</p>
              </div>
            </div>
            <div class="u-align-center u-container-style u-list-item u-repeater-item u-white u-list-item-2">
              <div class="u-container-layout u-similar-container u-valign-middle u-container-layout-2">
                <div alt class="u-image u-image-circle u-image-2"></div>
                <h3 class="u-custom-font u-text u-text-font u-text-palette-4-base u-text-5">Manicura y pedicura</h3>
                <p class="u-text u-text-palette-5-dark-1 u-text-6">$500</p>
              </div>
            </div>
            <div class="u-align-center u-container-style u-list-item u-repeater-item u-white u-list-item-3">
              <div class="u-container-layout u-similar-container u-valign-middle u-container-layout-3">
                <div alt class="u-image u-image-circle u-image-3"></div>
                <h3 class="u-custom-font u-text u-text-font u-text-palette-4-base u-text-7">Protección de la piel</h3>
                <p class="u-text u-text-palette-5-dark-1 u-text-8">$1400</p>
              </div>
            </div>
            <div class="u-align-center u-container-style u-list-item u-repeater-item u-white u-list-item-4">
              <div class="u-container-layout u-similar-container u-valign-middle u-container-layout-4">
                <div alt class="u-image u-image-circle u-image-4"></div>
                <h3 class="u-custom-font u-text u-text-font u-text-palette-4-base u-text-9">Maquillaje Profesional</h3>
                <p class="u-text u-text-palette-5-dark-1 u-text-10">$500-800</p>
              </div>
            </div>
          </div>

        </div>
      </section>
      <div class="separador"></div>
      <section class="u-align-center u-clearfix u-palette-3-light-3 u-section-5" id="somos">
        <div class="u-clearfix u-sheet u-sheet-1">
          <div class="u-expanded-width-lg u-shape u-shape-svg u-text-palette-3-light-2 u-shape-1">
            <svg class="u-svg-link" preserveAspectRatio="none" viewBox="0 0 160 80"><use href="#svg-aef1"/></svg>
          
          </div>
          <h3 class="u-align-center u-text u-text-palette-4-base u-text-1">Quienes somos?</h3>
          <div class="u-clearfix u-gutter-0 u-layout-wrap u-layout-wrap-1">
            <div class="u-layout">
              <div class="u-layout-row">
                <div class="u-align-left u-container-style u-layout-cell u-left-cell u-size-30 u-layout-cell-1">
                  <div class="u-container-layout u-valign-top u-container-layout-1">
                    <p class="u-text u-text-palette-4-dark-2 u-text-2">Somos una pequeña empresa con la visión de ayudar a mejor la apariencia de cada una de las personas que nos visiten, proporcionando un buen servicio dentro y fuera de nuestro establecimiento.</p>
                  </div>
                </div>

                <div class="u-align-left u-container-style u-layout-cell u-size-30 u-layout-cell-2">
                  <div class="u-container-layout u-valign-top u-container-layout-2">
                    <p class="u-text u-text-palette-4-dark-2 u-text-3">Ofreciendo desde nuestra plataforma un ambiente agradable y facil de usar, con la única intención que el cliente se sienta cómodo en toda la experiencia que se le ofrece en Velvet's Studio.
                      Tendiendo siempre como  prioridad a los clientes que llegan todos los días a nuestras manos.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
        
      </section>
      <div class="separador"></div>
      <section class="u-clearfix u-palette-4-base u-valign-middle u-section-6" id="ubicacion">
        <div class="five">
            <div id="MapaUTM">
                <h3 class="u-align-center u-text u-text-palette-4-base u-text-1">Ubicación</h3>
                <div class="centrado">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d30045.569226857577!2d-101.20027648916019!3d19.7255562!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x842d0e2765411ef1%3A0x3ca21a3f9f26b9b1!2sUniversidad%20Tecnol%C3%B3gica%20de%20Morelia!5e0!3m2!1ses-419!2smx!4v1717011573918!5m2!1ses-419!2smx" width="400" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
                <p style="color: rgb(0, 0, 0);" class="ubicacion-texto">Estamos ubicados en la Universidad Tecnológica de Morelia, una zona accesible y segura para todos nuestros visitantes.</p>
            </div>
        </div>
    </section>
    <div class="separador"></div>
      <section class="u-clearfix u-palette-3-light-2 u-section-7" id="contacto">
        <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
          <div class="u-clearfix u-expanded-width u-layout-wrap u-layout-wrap-1">
            <div class="u-layout">
              <div class="u-layout-row">
                <div class="u-container-style u-layout-cell u-left-cell u-size-25 u-layout-cell-1">
                  <div class="u-container-layout u-container-layout-1">
                    <h1 class="u-text u-text-palette-4-base u-text-1">¿Que necesitas?</h1>
                  </div>
                </div>
                <div class="u-container-style u-layout-cell u-size-15 u-layout-cell-2">
                  <div class="u-container-layout u-container-layout-2">
                    <ul class="u-text u-text-palette-4-base u-text-2">
                      <li> Manicuras <br/>
                      </li>
                      <li> Uñas acrílicas <br/>
                      </li>
                      <li> Uñas de gel </li>
                      <li> Pedicura <br/>
                      </li>
                    </ul>
                  </div>
                </div>
                <div class="u-container-style u-layout-cell u-right-cell u-size-20 u-layout-cell-3">
                  <div class="u-container-layout u-container-layout-3">
                    <p class="u-text u-text-3">Estás son algunos de los servicios que se le ofrecerán,  puede contactarnos por medio de los siguientes números: <br> 
                      +52 3344425252 <br>
                      +52 6263636373 <br> 
                      O puede agendar su cita en los  apartado siguiente.No dude en que se le ofrecerán un trato respetable y cálido para un mejor ambiente en su visita .</p>
                    <a href="contacto.php" class="u-btn u-button-style u-palette-4-base u-btn-1">Contáctenos</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <div class="separador"></div>
      <section class="team" id="equipo">
        <h2 style="color: #493273  ;">Nuestro Equipo</h2>
        <div class="person">
            <img src="imagenes/citlally.jpeg" alt="Persona 1">
            <p style="color: #493273;">Citlally</p>
        </div>
        <div class="person">
            <img src="imagenes/paulina.jpeg" alt="Persona 2">
            <p style="color:#493273;">Paulina</p>
        </div>
        <div class="person">
            <img src="imagenes/gael.jpeg" alt="Persona 3">
            <p style="color: #493273;">Gael</p>
        </div>
        <div class="person">
            <img src="imagenes/ernesto.jpeg" alt="Persona 4">
            <p style="color: #493273;">Ernesto</p>
        </div>
        <div class="person">
            <img src="imagenes/javier.jpeg" alt="Persona 5">
            <p style="color: #493273;">Javier</p>
        </div>
    </section>  
    <div class="separador"></div>
      <section class="u-clearfix u-expanded-width-xl u-palette-3-base u-section-9" id="carousel_6576">
        <div class="u-clearfix u-sheet u-sheet-1">
          <div class="u-absolute-hcenter-lg u-absolute-hcenter-xl u-expanded-height u-palette-4-base u-shape u-shape-rectangle u-shape-1"></div>
          <div class="u-clearfix u-expanded-width u-gutter-0 u-layout-wrap u-layout-wrap-1">
            <div class="u-gutter-0 u-layout">
              <div class="u-layout-row">
                <div class="u-size-27">
                  <div class="u-layout-col">
                    <div class="u-container-style u-layout-cell u-left-cell u-size-30 u-layout-cell-1">
                      <div class="u-container-layout u-valign-middle u-container-layout-1">
                        <h2 class="u-text u-text-palette-4-base u-text-1">ubicación</h2>
                        <p class="u-text u-text-palette-4-base u-text-2">Av, Vicepresidente Pino Suárez 750, Cd Industrial, 58200 Morelia, Mich. <br/> 
                        </p>
                      </div>
                    </div>
                    <div class="u-align-left u-container-style u-layout-cell u-left-cell u-size-30 u-layout-cell-2">
                      <div class="u-container-layout u-valign-middle u-container-layout-2">
                        <h2 class="u-text u-text-palette-4-base u-text-3">Síganos</h2>
                        <div class="u-social-icons u-spacing-20 u-text-palette-4-base u-social-icons-1">
                          <a class="u-social-url" target="_blank" href><span class="u-icon u-icon-circle u-social-facebook u-social-type-logo u-icon-1"><svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 112 112"><use href="#svg-ff39"/></svg><svg xmlns="http://www.w3.org/2000/svg" xlink="http://www.w3.org/1999/xlink" version="1.1" space="preserve" class="u-svg-content" viewBox="0 0 112 112" x="0px" y="0px" id="svg-ff39"><path d="M75.5,28.8H65.4c-1.5,0-4,0.9-4,4.3v9.4h13.9l-1.5,15.8H61.4v45.1H42.8V58.3h-8.8V42.4h8.8V32.2 c0-7.4,3.4-18.8,18.8-18.8h13.8v15.4H75.5z"/></svg>
                          
                          
                        </span>
                          </a>
                          <a class="u-social-url" target="_blank" href><span class="u-icon u-icon-circle u-social-twitter u-social-type-logo u-icon-2"><svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 112 112"><use href="#svg-5e85"/></svg><svg xmlns="http://www.w3.org/2000/svg" xlink="http://www.w3.org/1999/xlink" version="1.1" space="preserve" class="u-svg-content" viewBox="0 0 112 112" x="0px" y="0px" id="svg-5e85"><path d="M92.2,38.2c0,0.8,0,1.6,0,2.3c0,24.3-18.6,52.4-52.6,52.4c-10.6,0.1-20.2-2.9-28.5-8.2 c1.4,0.2,2.9,0.2,4.4,0.2c8.7,0,16.7-2.9,23-7.9c-8.1-0.2-14.9-5.5-17.3-12.8c1.1,0.2,2.4,0.2,3.4,0.2c1.6,0,3.3-0.2,4.8-0.7 c-8.4-1.6-14.9-9.2-14.9-18c0-0.2,0-0.2,0-0.2c2.5,1.4,5.4,2.2,8.4,2.3c-5-3.3-8.3-8.9-8.3-15.4c0-3.4,1-6.5,2.5-9.2 c9.1,11.1,22.7,18.5,38,19.2c-0.2-1.4-0.4-2.8-0.4-4.3c0.1-10,8.3-18.2,18.5-18.2c5.4,0,10.1,2.2,13.5,5.7c4.3-0.8,8.1-2.3,11.7-4.5 c-1.4,4.3-4.3,7.9-8.1,10.1c3.7-0.4,7.3-1.4,10.6-2.9C98.9,32.3,95.7,35.5,92.2,38.2z"/></svg>
                          
                          
                        </span>
                          </a>
                          <a class="u-social-url" target="_blank" href><span class="u-icon u-icon-circle u-social-instagram u-social-type-logo u-icon-3"><svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 112 112"><use href="#svg-1ef1"/></svg><svg xmlns="http://www.w3.org/2000/svg" xlink="http://www.w3.org/1999/xlink" version="1.1" space="preserve" class="u-svg-content" viewBox="0 0 112 112" x="0px" y="0px" id="svg-1ef1"><path d="M55.9,32.9c-12.8,0-23.2,10.4-23.2,23.2s10.4,23.2,23.2,23.2s23.2-10.4,23.2-23.2S68.7,32.9,55.9,32.9z M55.9,69.4c-7.4,0-13.3-6-13.3-13.3c-0.1-7.4,6-13.3,13.3-13.3s13.3,6,13.3,13.3C69.3,63.5,63.3,69.4,55.9,69.4z"/><path d="M79.7,26.8c-3,0-5.4,2.5-5.4,5.4s2.5,5.4,5.4,5.4c3,0,5.4-2.5,5.4-5.4S82.7,26.8,79.7,26.8z"/><path d="M78.2,11H33.5C21,11,10.8,21.3,10.8,33.7v44.7c0,12.6,10.2,22.8,22.7,22.8h44.7c12.6,0,22.7-10.2,22.7-22.7 V33.7C100.8,21.1,90.6,11,78.2,11z M91,78.4c0,7.1-5.8,12.8-12.8,12.8H33.5c-7.1,0-12.8-5.8-12.8-12.8V33.7 c0-7.1,5.8-12.8,12.8-12.8h44.7c7.1,0,12.8,5.8,12.8,12.8V78.4z"/></svg>
                          
                      
                        </span>
                          </a>
                          
                          
                          
                        </span>
                          </a>
                        </div>
                        
                      </div>
                    </div>
                  </div>
                </div>
                <div class="u-size-33">
                  <div class="u-layout-row">
                    <div class="u-container-style u-image u-layout-cell u-right-cell u-size-60 u-image-1">
                      <div class="u-container-layout u-container-layout-3"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      
      
  <footer>
  <?php include 'footer.php'; ?>
      </footer>
     
    
  </body></html>