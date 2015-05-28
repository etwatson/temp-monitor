# Start a session because the server needs to be able to link the nonce request and the actual data post request.
session = requests.Session()
 
# Getting a fresh nonce which we will use in the authentication step.
nonce = session.get(url='url_to_server_side_script?step=nonce').text
 
# Hashing the nonce, the password and the temperature values (to provide some integrity).
response = hashlib.sha256(nonce + 'PASSWORD' + str(avgtemperatures[0]) + str(avgtemperatures[1])).hexdigest()
 
# Post data of the two temperature values and the authentication response.
post_data = {'response':response, 'temp1':avgtemperatures[0], 'temp2': avgtemperatures[1]}
 
post_request = session.post(url='url_to_server_side_script', data=post_data)
