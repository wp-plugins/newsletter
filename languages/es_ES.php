<?php

$defaults_profile = array();
$defaults_profile['email_error'] = 'Direccion de correo electronico incorrecta.';
$defaults_profile['name_error'] = 'El nombre no puede estar vacio.';

$defaults = array();
// Suscripcion pagina de introduccion de texto (formulario de suscripcion)
$defaults['subscription_text'] =
"<p>Suscribirse a mi boletín llenando el formulario a continuacion.
Voy a tratar de hacerte feliz.</p>
<p>Un correo de confirmacion será enviado a su buzon de correo:
por favor, lea las instrucciones en su interior para completar la suscripcion.</p>";

// Mostrar mensaje despues de una solicitud de suscripcion hecha.
$defaults['subscribed_text'] =
"<p>Con exito suscrito a mi boletín informativo.
Usted recibirá en pocos minutos un email de confirmacion. Siga el enlace
en el para confirmar la suscripcion. Si el correo tarda mas de 15
minutos en aparecer en su buzon de correo, revise la carpeta de spam.</p>";

// Tema de correo electronico de confirmacion (double opt-in)
$defaults['confirmation_subject'] =
"{name}, confirmar su suscripcion a {blog_title}";

// Cuerpo confirmacion por correo electronico (double opt-in)
$defaults['confirmation_message'] =
"<p>Hola {name},</p>
<p>He recibido una solicitud de suscripcion para esta direccion de correo electronico. Usted puede confirmar:
<a href=\"{subscription_confirm_url}\"><strong>click aquí</strong></a>.
Si usted no puede hacer click en el enlace, utilice el siguiente enlace:</p>
<p>{subscription_confirm_url}</p>
<p>Si esta solicitud de suscripcion no se ha hecho de usted, simplemente ignore este mensaje.</p>
<p>Gracias.</p>";


// Suscripcion confirmacion texto (despues de que un usuario hace clic en el enlace de confirmacion
// en el correo electronico que recibira
$defaults['confirmed_text'] =
"<p>Su suscripcion se ha confirmado!
Gracias {name}!</p>";

$defaults['confirmed_subject'] =
"Bienvenido a bordo, {name}";

$defaults['confirmed_message'] =
"<p>El mensaje de confirmar su suscripcion a {blog_title} newsletter.</p>
<p>Gracias!</p>";

// Darse de baja de la solicitud introduccion de texto
$defaults['unsubscription_text'] =
"<p>Por favor, confirme que desea darse de baja mi boletín de noticias
<a href=\"{unsubscription_confirm_url}\">click aquí</a>.";


$defaults['unsubscribed_text'] =
"<p>Me hace llorar, pero he quitado su suscripcion ...</p>";

$defaults['unsubscribed_subject'] =
"Adios, {name}";

$defaults['unsubscribed_message'] =
"<p>Mensaje de confirmacion de su baja en {blog_title} Boletín de noticias.</p>
<p>Adios!</p>";


