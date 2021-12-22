<?php
require_once('connection.php');

    class Customer extends DB {

        public int $customerID;
        public string $firstName;
        public string $lastName;
        public string $company;
        public string $address;
        public string $city;
        public string $state;
        public string $country;
        public string $postalCode;
        public string $phone;
        public string $fax;
        public string $email;


        /**
         * Inserts a new customer
         */
        function create($firstName, $lastName, $password, $company, $address, $city, $state, $country, $postalCode, $phone, $fax, $email){

            //Check if the customer already exists
            $query = <<<'SQL'
                SELECT COUNT(*) as total FROM customer WHERE email = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$email]);
            if ($stmt->fetch()['total'] > 0) {
                return false;
            }

            //insert the user
            $password = password_hash($password, PASSWORD_DEFAULT);

            $query = <<<'SQL'
                INSERT INTO customer (FirstName, LastName, Password, Company, Address, City, State, Country, PostalCode, Phone, Fax, Email) VALUES (?,?,?,?,?,?,?,?,?,?,?,?);
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$firstName, $lastName, $password, $company, $address, $city, $state, $country, $postalCode, $phone, $fax, $email]);

            $this->disconnect();

            return true;
        }

        /**
         * Updates a customer
         */
        function update($firstName, $lastName, $password, $newPassword, $company, $address, $city, $state, $country, $postalCode, $phone, $fax, $email){

            $changePassword = (trim($newPassword) !== '');

            //Might have to move Password to after LastName if it causes an error because of the order.
            $query = <<<'SQL'
                UPDATE customer
                SET FirstName = ?,
                    LastName = ?,
                    Company = ?,
                    Address = ?,
                    City = ?,
                    State = ?,
                    Country = ?,
                    PostalCode = ?,
                    Phone = ?,
                    Fax = ?
            SQL;

            if ($changePassword) {
                if ($this->validate($email, $password)) {
                    $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $query .= ', Password = ?';
                }
                else {
                    return false;
                }
            }
            $query .= ' WHERE Email = ?;';

            $stmt = $this->pdo->prepare($query);
            if ($changePassword) {
                $stmt->execute([$firstName, $lastName, $company, $address, $city, $state, $country, $postalCode, $phone, $fax, $newPassword, $email]);
            }
            else {
                $stmt->execute([$firstName, $lastName, $company, $address, $city, $state, $country, $postalCode, $phone, $fax, $email]);
            }

            $this->disconnect();

            return true;
        }



        /**
         * Validates a customer
         */
        function validate($email, $password){
            //Get customer data
            $query = <<<'SQL'
                SELECT CustomerId, FirstName, LastName, Password, Company, Address, City, State, Country, PostalCode, Phone, Fax FROM customer WHERE Email = ?;
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$email]);

            if ($stmt->rowCount() === 0) {
                return false;
            }

            $row = $stmt->fetch();

            $this->customerID = $row['CustomerId'];
            $this->firstName = $row['FirstName'];
            $this->lastName = $row['LastName'];
            $this->company = $row['Company'] ?? '';
            $this->address = $row['Address'] ?? '';
            $this->city = $row['City'] ?? '';
            $this->state = $row['State'] ?? '';
            $this->country = $row['Country'] ?? '';
            $this->postalCode = $row['PostalCode'] ?? '';
            $this->phone = $row['Phone'] ?? '';
            $this->fax = $row['Fax'] ?? '';
            $this->email = $email;

            //Check password
            return (password_verify($password, $row['Password']));
        }


    }

?>