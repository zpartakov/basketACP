    @package basketACP
    @version $Id: 1.0.0
    @author Fred Radeff fradeff@akademia.ch
    @copyright (c) 2014 Fred Radeff, akademia.ch
    @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later

Software for admin + basket of products for a contract farming organisation 
Authentication based on Joomla!

Logiciel de gestion pour des commandes de produits divers
pour une coopérative d'agriculture contractuelle de proximité (ACP) 

Authentification basée sur Joomla!

![basketACP snapshot](/basketACP.png?raw=true "basketACP")


######### HOWTO + infos #################
This software is a fork based on
Plaincart  v 1.1.2
https://github.com/sattip/upload_form/tree/master/plaincart

Required:
connection to joomla cms users (hallo component)
see https://github.com/zpartakov/cocagne

- connection to joomla cms users (hallo component)

    * /var/www/cocagne/cms/components/com_hallo/views/hallo/tmp/default.php

le composant "hallo" qui permet de récupérer l'authentification Joomla 

READ
- change banner: /admin/include/banner-top.gif
------------------
todos:
...
---
/var/www/cocagne/commandes (ROOT URL)


checkout.php recapitulatif (nothing to change)
cart.php the basket / card info
success.php IMPORTANT email + divers - le principal à changer en général


/var/www/cocagne/commandes/include/

shippingAndPaymentInfo.php recapitulatif avant envoi final IMPORTANT

miniCart.php la petite fenêtre flottante qui apparaît dans les commandes, avant récapitulatif

/var/www/cocagne/commandes/admin/library/functions.php contient notamment le calculateur du jeudi de livraison, get_lundi_dimanche_from_week

#############

https://github.com/sattip/upload_form/tree/master/plaincart

=====================================================
=                                                   =
=                                                   =
=  Plaincart  v 1.1.2                               =
=                                                   =
=                                                   =
=====================================================

_____________________________________________________

Plaincart is free to use, modify or enchance. Just 
remember that it's created as an example for
the shopping cart tutorial in www.phpwebcommerce.com 
so i disclaim anything if you want to use it on a 
live site
_____________________________________________________


INSTALLATION INSTRUCTIONS

1.) Unzip plaincart.zip to the root folder under your 
    HTTP directory ( or under your preferred directory) 


2.) Create a database and database user on your web
    server for Plaincart

3.) Use the sql dump in plaincart.sql to generate the
    tables and example data

4.) Modify the database connection settings in 
    library/variables.inc.php.

5.) If you want to accept paypal modify the settings
    in include/paypal/paypal.inc.php . More information
    about this paypal stuff can be found in 
    http://www.phpwebcommerce.com/shop-checkout-process/



You can start playing with the shopping cart by
login to the administrator page. The default 
id and password is 'admin' ( without the quotes )
_____________________________________________________

Zpartakov's install notes

the original software can be found at https://github.com/sattip/upload_form/tree/master/plaincart

the original software is in the current directory, unchanged, file 
plaincart.zip

it has been modified by Zpartakov for Cocagne's purposes, main modif are:

- translation in french of both user / admin area
- connection to joomla cms users (hallo component)
- connection to ad hoc users's adresses table 
- registration redirection for user who don't have an account
- adding 2 more fields (place and date of delivery) for each order
- css adaptation

