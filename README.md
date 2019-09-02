# laravel-keycloak-admin



add these environment variables to your .env :



KEYCLOAK_BASE_URL=http://keycloak-domain.example/auth

KEYCLOAK_REALM=                

KEYCLOAK_REALM_PUBLIC_KEY=     // realm settings -> keys  

KEYCLOAK_CLIENT_ID=            

KEYCLOAK_CLIENT_SECRET=       // clients -> your_client -> credentials

KEYCLOAK_ADMIN_BASE_URL=${KEYCLOAK_BASE_URL}/admin/realms/${KEYCLOAK_REALM} 




Enable realm managment :

go to clients -> your_client -> Service Account Roles 

from Client Roles list select realm-managment and assign realm-admin to client






available methods : 


package has provided services as below :

user
role
client
clientRole




all api`s are in config\keycloakAdmin.php
 
for every api just call api name as method on related service .



example:

KeycloakAdmin::serviceName()->apiName($parameters)



keycloakAdmin::user()->create([
      
     'body' => [  // https://www.keycloak.org/docs-api/7.0/rest-api/index.html#_userrepresentation
             
             'username' => 'foo'
              
       ]

]);



keycloakAdmin::user()->update([

     'id' => 'user_id',

     'body' => [  // https://www.keycloak.org/docs-api/7.0/rest-api/index.html#_userrepresentation
             
             'username' => 'foo'
              
       ]

]);



keycloakAdmin::role()->get([
      
     'id' => 'role_id'

]);


All other api calls are same just need to see required parameters in https://www.keycloak.org/docs-api/7.0/rest-api/index.html
