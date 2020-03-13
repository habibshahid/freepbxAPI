<?php

namespace App\Controllers;

class FreePbxController extends Controller
{
    public function __construct($container){
        parent::__construct($container);
        global $chan_drivers, $destinationTypes, $timezones, $followMeStrategies;

        $chan_drivers = array(
            'sip' => 'chan_sip',
            'pjsip' => 'chan_pjsip',
            'SIP' => 'chan_sip',
            'PJSIP' => 'chan_pjsip',
        );
        $destinationTypes = array(
            'announcement'      =>  'app-announcement-{destination},s,1',
            'callback'          =>  'callback,{destination},1',
            'conference'        =>  'ext-meetme,{destination},1',
            'extension'         =>  'from-did-direct,{destination},1',
            'ivr'               =>  'ivr-{destination},s,1',
            'inbound_route'     =>  'from-trunk,{destination},1',
            'queue'             =>  'ext-queues,{destination},1',
            'ring_group'        =>  'ext-group,{destination},1',
            'terminate'         =>  'app-blackhole,{destination},1',
            'time_condition'    =>  'timeconditions,{destination},1',
            'trunks'            =>  'ext-trunk,{destination},1'
        );
        $timezones = array(
            "default",
            "Africa/Abidjan",
            "Africa/Accra",
            "Africa/Addis_Ababa",
            "Africa/Algiers",
            "Africa/Asmara",
            "Africa/Bamako",
            "Africa/Bangui",
            "Africa/Banjul",
            "Africa/Bissau",
            "Africa/Blantyre",
            "Africa/Brazzaville",
            "Africa/Bujumbura",
            "Africa/Cairo",
            "Africa/Casablanca",
            "Africa/Ceuta",
            "Africa/Conakry",
            "Africa/Dakar",
            "Africa/Dar_es_Salaam",
            "Africa/Djibouti",
            "Africa/Douala",
            "Africa/El_Aaiun",
            "Africa/Freetown",
            "Africa/Gaborone",
            "Africa/Harare",
            "Africa/Johannesburg",
            "Africa/Juba",
            "Africa/Kampala",
            "Africa/Khartoum",
            "Africa/Kigali",
            "Africa/Kinshasa",
            "Africa/Lagos",
            "Africa/Libreville",
            "Africa/Lome",
            "Africa/Luanda",
            "Africa/Lubumbashi",
            "Africa/Lusaka",
            "Africa/Malabo",
            "Africa/Maputo",
            "Africa/Maseru",
            "Africa/Mbabane",
            "Africa/Mogadishu",
            "Africa/Monrovia",
            "Africa/Nairobi",
            "Africa/Ndjamena",
            "Africa/Niamey",
            "Africa/Nouakchott",
            "Africa/Ouagadougou",
            "Africa/Porto-Novo",
            "Africa/Sao_Tome",
            "Africa/Tripoli",
            "Africa/Tunis",
            "Africa/Windhoek",
            "America/Adak",
            "America/Anchorage",
            "America/Anguilla",
            "America/Antigua",
            "America/Araguaina",
            "America/Argentina/Buenos_Aires",
            "America/Argentina/Catamarca",
            "America/Argentina/Cordoba",
            "America/Argentina/Jujuy",
            "America/Argentina/La_Rioja",
            "America/Argentina/Mendoza",
            "America/Argentina/Rio_Gallegos",
            "America/Argentina/Salta",
            "America/Argentina/San_Juan",
            "America/Argentina/San_Luis",
            "America/Argentina/Tucuman",
            "America/Argentina/Ushuaia",
            "America/Aruba",
            "America/Asuncion",
            "America/Atikokan",
            "America/Bahia",
            "America/Bahia_Banderas",
            "America/Barbados",
            "America/Belem",
            "America/Belize",
            "America/Blanc-Sablon",
            "America/Boa_Vista",
            "America/Bogota",
            "America/Boise",
            "America/Cambridge_Bay",
            "America/Campo_Grande",
            "America/Cancun",
            "America/Caracas",
            "America/Cayenne",
            "America/Cayman",
            "America/Chicago",
            "America/Chihuahua",
            "America/Costa_Rica",
            "America/Creston",
            "America/Cuiaba",
            "America/Curacao",
            "America/Danmarkshavn",
            "America/Dawson",
            "America/Dawson_Creek",
            "America/Denver",
            "America/Detroit",
            "America/Dominica",
            "America/Edmonton",
            "America/Eirunepe",
            "America/El_Salvador",
            "America/Fort_Nelson",
            "America/Fortaleza",
            "America/Glace_Bay",
            "America/Godthab",
            "America/Goose_Bay",
            "America/Grand_Turk",
            "America/Grenada",
            "America/Guadeloupe",
            "America/Guatemala",
            "America/Guayaquil",
            "America/Guyana",
            "America/Halifax",
            "America/Havana",
            "America/Hermosillo",
            "America/Indiana/Indianapolis",
            "America/Indiana/Knox",
            "America/Indiana/Marengo",
            "America/Indiana/Petersburg",
            "America/Indiana/Tell_City",
            "America/Indiana/Vevay",
            "America/Indiana/Vincennes",
            "America/Indiana/Winamac",
            "America/Inuvik",
            "America/Iqaluit",
            "America/Jamaica",
            "America/Juneau",
            "America/Kentucky/Louisville",
            "America/Kentucky/Monticello",
            "America/Kralendijk",
            "America/La_Paz",
            "America/Lima",
            "America/Los_Angeles",
            "America/Lower_Princes",
            "America/Maceio",
            "America/Managua",
            "America/Manaus",
            "America/Marigot",
            "America/Martinique",
            "America/Matamoros",
            "America/Mazatlan",
            "America/Menominee",
            "America/Merida",
            "America/Metlakatla",
            "America/Mexico_City",
            "America/Miquelon",
            "America/Moncton",
            "America/Monterrey",
            "America/Montevideo",
            "America/Montserrat",
            "America/Nassau",
            "America/New_York",
            "America/Nipigon",
            "America/Nome",
            "America/Noronha",
            "America/North_Dakota/Beulah",
            "America/North_Dakota/Center",
            "America/North_Dakota/New_Salem",
            "America/Ojinaga",
            "America/Panama",
            "America/Pangnirtung",
            "America/Paramaribo",
            "America/Phoenix",
            "America/Port-au-Prince",
            "America/Port_of_Spain",
            "America/Porto_Velho",
            "America/Puerto_Rico",
            "America/Punta_Arenas",
            "America/Rainy_River",
            "America/Rankin_Inlet",
            "America/Recife",
            "America/Regina",
            "America/Resolute",
            "America/Rio_Branco",
            "America/Santarem",
            "America/Santiago",
            "America/Santo_Domingo",
            "America/Sao_Paulo",
            "America/Scoresbysund",
            "America/Sitka",
            "America/St_Barthelemy",
            "America/St_Johns",
            "America/St_Kitts",
            "America/St_Lucia",
            "America/St_Thomas",
            "America/St_Vincent",
            "America/Swift_Current",
            "America/Tegucigalpa",
            "America/Thule",
            "America/Thunder_Bay",
            "America/Tijuana",
            "America/Toronto",
            "America/Tortola",
            "America/Vancouver",
            "America/Whitehorse",
            "America/Winnipeg",
            "America/Yakutat",
            "America/Yellowknife",
            "Antarctica/Casey",
            "Antarctica/Davis",
            "Antarctica/DumontDUrville",
            "Antarctica/Macquarie",
            "Antarctica/Mawson",
            "Antarctica/McMurdo",
            "Antarctica/Palmer",
            "Antarctica/Rothera",
            "Antarctica/Syowa",
            "Antarctica/Troll",
            "Antarctica/Vostok",
            "Arctic/Longyearbyen",
            "Asia/Aden",
            "Asia/Almaty",
            "Asia/Amman",
            "Asia/Anadyr",
            "Asia/Aqtau",
            "Asia/Aqtobe",
            "Asia/Ashgabat",
            "Asia/Atyrau",
            "Asia/Baghdad",
            "Asia/Bahrain",
            "Asia/Baku",
            "Asia/Bangkok",
            "Asia/Barnaul",
            "Asia/Beirut",
            "Asia/Bishkek",
            "Asia/Brunei",
            "Asia/Chita",
            "Asia/Choibalsan",
            "Asia/Colombo",
            "Asia/Damascus",
            "Asia/Dhaka",
            "Asia/Dili",
            "Asia/Dubai",
            "Asia/Dushanbe",
            "Asia/Famagusta",
            "Asia/Gaza",
            "Asia/Hebron",
            "Asia/Ho_Chi_Minh",
            "Asia/Hong_Kong",
            "Asia/Hovd",
            "Asia/Irkutsk",
            "Asia/Jakarta",
            "Asia/Jayapura",
            "Asia/Jerusalem",
            "Asia/Kabul",
            "Asia/Kamchatka",
            "Asia/Karachi",
            "Asia/Kathmandu",
            "Asia/Khandyga",
            "Asia/Kolkata",
            "Asia/Krasnoyarsk",
            "Asia/Kuala_Lumpur",
            "Asia/Kuching",
            "Asia/Kuwait",
            "Asia/Macau",
            "Asia/Magadan",
            "Asia/Makassar",
            "Asia/Manila",
            "Asia/Muscat",
            "Asia/Nicosia",
            "Asia/Novokuznetsk",
            "Asia/Novosibirsk",
            "Asia/Omsk",
            "Asia/Oral",
            "Asia/Phnom_Penh",
            "Asia/Pontianak",
            "Asia/Pyongyang",
            "Asia/Qatar",
            "Asia/Qostanay",
            "Asia/Qyzylorda",
            "Asia/Riyadh",
            "Asia/Sakhalin",
            "Asia/Samarkand",
            "Asia/Seoul",
            "Asia/Shanghai",
            "Asia/Singapore",
            "Asia/Srednekolymsk",
            "Asia/Taipei",
            "Asia/Tashkent",
            "Asia/Tbilisi",
            "Asia/Tehran",
            "Asia/Thimphu",
            "Asia/Tokyo",
            "Asia/Tomsk",
            "Asia/Ulaanbaatar",
            "Asia/Urumqi",
            "Asia/Ust-Nera",
            "Asia/Vientiane",
            "Asia/Vladivostok",
            "Asia/Yakutsk",
            "Asia/Yangon",
            "Asia/Yekaterinburg",
            "Asia/Yerevan",
            "Atlantic/Azores",
            "Atlantic/Bermuda",
            "Atlantic/Canary",
            "Atlantic/Cape_Verde",
            "Atlantic/Faroe",
            "Atlantic/Madeira",
            "Atlantic/Reykjavik",
            "Atlantic/South_Georgia",
            "Atlantic/St_Helena",
            "Atlantic/Stanley",
            "Australia/Adelaide",
            "Australia/Brisbane",
            "Australia/Broken_Hill",
            "Australia/Currie",
            "Australia/Darwin",
            "Australia/Eucla",
            "Australia/Hobart",
            "Australia/Lindeman",
            "Australia/Lord_Howe",
            "Australia/Melbourne",
            "Australia/Perth",
            "Australia/Sydney",
            "Europe/Amsterdam",
            "Europe/Andorra",
            "Europe/Astrakhan",
            "Europe/Athens",
            "Europe/Belgrade",
            "Europe/Berlin",
            "Europe/Bratislava",
            "Europe/Brussels",
            "Europe/Bucharest",
            "Europe/Budapest",
            "Europe/Busingen",
            "Europe/Chisinau",
            "Europe/Copenhagen",
            "Europe/Dublin",
            "Europe/Gibraltar",
            "Europe/Guernsey",
            "Europe/Helsinki",
            "Europe/Isle_of_Man",
            "Europe/Istanbul",
            "Europe/Jersey",
            "Europe/Kaliningrad",
            "Europe/Kiev",
            "Europe/Kirov",
            "Europe/Lisbon",
            "Europe/Ljubljana",
            "Europe/London",
            "Europe/Luxembourg",
            "Europe/Madrid",
            "Europe/Malta",
            "Europe/Mariehamn",
            "Europe/Minsk",
            "Europe/Monaco",
            "Europe/Moscow",
            "Europe/Oslo",
            "Europe/Paris",
            "Europe/Podgorica",
            "Europe/Prague",
            "Europe/Riga",
            "Europe/Rome",
            "Europe/Samara",
            "Europe/San_Marino",
            "Europe/Sarajevo",
            "Europe/Saratov",
            "Europe/Simferopol",
            "Europe/Skopje",
            "Europe/Sofia",
            "Europe/Stockholm",
            "Europe/Tallinn",
            "Europe/Tirane",
            "Europe/Ulyanovsk",
            "Europe/Uzhgorod",
            "Europe/Vaduz",
            "Europe/Vatican",
            "Europe/Vienna",
            "Europe/Vilnius",
            "Europe/Volgograd",
            "Europe/Warsaw",
            "Europe/Zagreb",
            "Europe/Zaporozhye",
            "Europe/Zurich",
            "Indian/Antananarivo",
            "Indian/Chagos",
            "Indian/Christmas",
            "Indian/Cocos",
            "Indian/Comoro",
            "Indian/Kerguelen",
            "Indian/Mahe",
            "Indian/Maldives",
            "Indian/Mauritius",
            "Indian/Mayotte",
            "Indian/Reunion",
            "Pacific/Apia",
            "Pacific/Auckland",
            "Pacific/Bougainville",
            "Pacific/Chatham",
            "Pacific/Chuuk",
            "Pacific/Easter",
            "Pacific/Efate",
            "Pacific/Enderbury",
            "Pacific/Fakaofo",
            "Pacific/Fiji",
            "Pacific/Funafuti",
            "Pacific/Galapagos",
            "Pacific/Gambier",
            "Pacific/Guadalcanal",
            "Pacific/Guam",
            "Pacific/Honolulu",
            "Pacific/Kiritimati",
            "Pacific/Kosrae",
            "Pacific/Kwajalein",
            "Pacific/Majuro",
            "Pacific/Marquesas",
            "Pacific/Midway",
            "Pacific/Nauru",
            "Pacific/Niue",
            "Pacific/Norfolk",
            "Pacific/Noumea",
            "Pacific/Pago_Pago",
            "Pacific/Palau",
            "Pacific/Pitcairn",
            "Pacific/Pohnpei",
            "Pacific/Port_Moresby",
            "Pacific/Rarotonga",
            "Pacific/Saipan",
            "Pacific/Tahiti",
            "Pacific/Tarawa",
            "Pacific/Tongatapu",
            "Pacific/Wake",
            "Pacific/Wallis",
            "UTC"
        );
        $followMeStrategies = array(
            'ringallv2',
            'ringallv2-prim',
            'ringall',
            'ringall-prim',
            'hunt',
            'hunt-prim',
            'memoryhunt',
            'memoryhunt-prim',
            'firstavailable',
            'firstnotonphone'
        );
    }

    //Helper Functions
    private function getDestinationIdByType($type, $name){
        switch($type){
            case 'announcement':
                $sql = "SELECT announcement_id as id FROM announcement where description = '". $name ."';";
                $stmt = $this->c->db->query($sql);
                $result = $stmt->fetch();
                return $result['id'];
                break;
            case 'callback':
                $sql = "SELECT callback_id as id FROM callback where description = '". $name ."';";
                $stmt = $this->c->db->query($sql);
                $result = $stmt->fetch();
                return $result['id'];
                break;
            case 'conference':
                $sql = "SELECT exten as id FROM meetme where exten = '". $name ."';";
                $stmt = $this->c->db->query($sql);
                $result = $stmt->fetch();
                return $result['id'];
                break;
            case 'extension':
                $sql = "SELECT id as id FROM devices where id = '". $name ."';";
                $stmt = $this->c->db->query($sql);
                $result = $stmt->fetch();
                return $result['id'];
                break;
            case 'ivr':
                $sql = "SELECT id as id FROM ivr_details where name = '". $name ."';";
                $stmt = $this->c->db->query($sql);
                $result = $stmt->fetch();
                return $result['id'];
                break;
            case 'queue':
                $sql = "SELECT extension as id FROM queues_config where extension = '". $name ."';";
                $stmt = $this->c->db->query($sql);
                $result = $stmt->fetch();
                return $result['id'];
                break;
            case 'ring_group':
                $sql = "SELECT grpnum as id FROM ringgroups where grpnum = '". $name ."';";
                $stmt = $this->c->db->query($sql);
                $result = $stmt->fetch();
                return $result['id'];
                break;
            case 'terminate':
                return $name;
                break;
            case 'time_condition':
                $sql = "SELECT timeconditions_id as id FROM timeconditions where displayname = '". $name ."';";
                $stmt = $this->c->db->query($sql);
                $result = $stmt->fetch();
                return $result['id'];
                break;
            case 'time_group':
                $sql = "SELECT id as id FROM timegroups_groups where description = '". $name ."';";
                $stmt = $this->c->db->query($sql);
                $result = $stmt->fetch();
                return $result['id'];
                break;
            case 'trunks':
                $sql = "SELECT trunkid as id FROM trunks where name = '". $name ."';";
                $stmt = $this->c->db->query($sql);
                $result = $stmt->fetch();
                return $result['id'];
                break;
        }
    }
    private function getTrunkId(){
        $sql = "SELECT trunkid FROM trunks order by trunkid desc limit 1;";
        $stmt = $this->c->db->query($sql);
        $trunkId = $stmt->fetch();

        return $trunkId['trunkid'];
    }
    private function matchVariables($data = '', $variable = array()){
        $customVars = $variable;
        $temp_vars = NULL;
        $match_vars = NULL;
        $m = array();

        $arr = preg_match_all('/(?<={)[^}]+(?=})/', $data, $m) ? $m[0] : Array();
        foreach($arr as $arr_row){
            $temp_variable = $arr_row;

            if($customVars[$arr_row] == ''){
                $temp_vars[] = "/"."{".preg_quote($arr_row)."}"."/";
                $cc = strtok($arr_row, '[');
                $cc_arr = explode($cc,$arr_row);
                $keys = explode('][', trim($cc_arr[1], '[]'));

                if(count($keys) == 1){
                    $varRes = $customVars[$cc][$keys[0]];
                }elseif(count($keys) == 2){
                    $varRes = $customVars[$cc][$keys[0]][$keys[1]];
                }elseif(count($keys) == 3){
                    $varRes = $customVars[$cc][$keys[0]][$keys[1]][$keys[2]];
                }elseif(count($keys) == 4){
                    $varRes = $customVars[$cc][$keys[0]][$keys[1]][$keys[2]][$keys[3]];
                }elseif(count($keys) == 5){
                    $varRes = $customVars[$cc][$keys[0]][$keys[1]][$keys[2]][$keys[3]][$keys[4]];
                }elseif(count($keys) == 6){
                    $varRes = $customVars[$cc][$keys[0]][$keys[1]][$keys[2]][$keys[3]][$keys[4]][$keys[5]];
                }
                $match_vars[] = "".$varRes."";
            }else{
                $temp_vars[] = "/"."{".preg_quote($arr_row)."}"."/";
                $match_vars[] = "".$customVars[$arr_row]."";
            }
        }

        $variablesString = preg_replace($temp_vars, $match_vars, $data);

        if($variablesString){
            $variablesString = $variablesString;
        }elseif($variablesString == ''){
            $variablesString = $data;
        }

        return $variablesString;
    }
    private function checkDuplicateExtension($extension)
    {
        $sql = "SELECT * FROM sip where id = '" . $extension . "';";
        $stmt = $this->c->db->query($sql);
        $sipExtensions = $stmt->fetchAll();

        if (count($sipExtensions) > 0) {
            return true;
        } else {
            return false;
        }
    }
    private function checkDuplicateDevice($extension)
    {
        $sql = "SELECT * FROM devices where id = '" . $extension . "';";
        $stmt = $this->c->db->query($sql);
        $sipExtensions = $stmt->fetchAll();

        if (count($sipExtensions) > 0) {
            return true;
        } else {
            return false;
        }
    }
    private function checkDuplicateFollowMe($extension)
    {
        $sql = "SELECT * FROM findmefollow where grpnum = '" . $extension . "';";
        $stmt = $this->c->db->query($sql);
        $sipExtensions = $stmt->fetchAll();

        if (count($sipExtensions) > 0) {
            return true;
        } else {
            return false;
        }
    }
    private function checkDuplicateUCP($extension)
    {
        $sql = "SELECT * FROM userman_users where username = '" . $extension . "';";
        $stmt = $this->c->db->query($sql);
        $sipExtensions = $stmt->fetchAll();

        if (count($sipExtensions) > 0) {
            return true;
        } else {
            return false;
        }
    }
    private function checkDuplicateUser($extension)
    {
        $sql = "SELECT * FROM users where extension = '" . $extension . "';";
        $stmt = $this->c->db->query($sql);
        $sipExtensions = $stmt->fetchAll();

        if (count($sipExtensions) > 0) {
            return true;
        } else {
            return false;
        }
    }
    private function checkDuplicateTrunk($name)
    {
        $sql = "SELECT * FROM trunks where name = '" . $name . "';";
        $stmt = $this->c->db->query($sql);
        $duplicate = $stmt->fetchAll();

        if (count($duplicate) > 0) {
            return true;
        } else {
            return false;
        }
    }
    private function checkDuplicateInboundRouteDID($did){
        $sql = "SELECT * FROM incoming where extension = '" . $did . "';";
        $stmt = $this->c->db->query($sql);
        $duplicate = $stmt->fetchAll();

        if (count($duplicate) > 0) {
            return true;
        } else {
            return false;
        }
    }
    private function checkDuplicateInboundRouteName($description){
        $sql = "SELECT * FROM incoming where description = '" . $description . "';";
        $stmt = $this->c->db->query($sql);
        $duplicate = $stmt->fetchAll();

        if (count($duplicate) > 0) {
            return true;
        } else {
            return false;
        }
    }
    private function checkDuplicateUserContext($name){
        $sql = "SELECT * FROM trunks where usercontext = '" . $name . "';";
        $stmt = $this->c->db->query($sql);
        $duplicate = $stmt->fetchAll();

        if (count($duplicate) > 0) {
            return true;
        } else {
            return false;
        }
    }
    private function checkDuplicatePeerContext($name)
    {
        $sql = "SELECT * FROM trunks where channelid = '" . $name . "';";
        $stmt = $this->c->db->query($sql);
        $duplicate = $stmt->fetchAll();

        if (count($duplicate) > 0) {
            return true;
        } else {
            return false;
        }
    }
    private function checkDuplicateDialPattern($dialPattern, $routeId){
        $sql = "SELECT * FROM outbound_route_patterns where route_id = '". $routeId ."' and match_pattern_prefix = '". $dialPattern['match_pattern_prefix'] ."' and match_pattern_pass = '". $dialPattern['match_pattern_pass'] ."' and match_cid = '". $dialPattern['match_cid'] ."' and prepend_digits = '". $dialPattern['prepend_digits'] ."';";
        $stmt = $this->c->db->query($sql);
        $result = $stmt->fetchAll();

        if(count($result) > 0){
            return true;
        }
        else{
            return false;
        }
    }
    private function checkDuplicateOutboundRouteName($name){
        $sql = "SELECT * FROM outbound_routes where name = '" . $name . "';";
        $stmt = $this->c->db->query($sql);
        $duplicate = $stmt->fetchAll();

        if (count($duplicate) > 0) {
            return true;
        } else {
            return false;
        }
    }
    //End Helper Functions

    //Extensions
    public function getAllSIPExtensions($request, $response){
        $sql = "SELECT * FROM devices;";
        $stmt = $this->c->db->query($sql);
        $sipExtensions = $stmt->fetchAll();

        return $response->withJson(array(
            'code' => 200,
            'data' => $sipExtensions
        ));
    }
    public function createSIPExtension($request, $response){
        global $chan_drivers, $followMeStrategies, $destinationTypes;

        $body = $request->getParsedBody();

        if (!isset($body['extension']) || $body['extension'] == '') {
            return $response->withJson(array(
                'code' => 501,
                'data' => 'Extension not defined.'
            ));
        } elseif (!isset($body['displayname']) || $body['displayname'] == '') {
            return $response->withJson(array(
                'code' => 501,
                'data' => 'Display Name not defined.'
            ));
        } elseif (!isset($body['devicetype']) || ($body['devicetype'] == '' || !array_key_exists($body['devicetype'], $chan_drivers))) {
            return $response->withJson(array(
                'code' => 501,
                'data' => 'Device Type not defined. Possible Types (SIP/PJSIP)'
            ));
        } elseif (!isset($body['secret']) || $body['secret'] == '') {
            return $response->withJson(array(
                'code' => 501,
                'data' => 'Secret not defined.'
            ));
        }

        $duplicate['extension'] = $this->checkDuplicateExtension($body['extension']);
        $duplicate['device'] = $this->checkDuplicateDevice($body['extension']);
        $duplicate['followme'] = $this->checkDuplicateFollowMe($body['extension']);
        $duplicate['ucp'] = $this->checkDuplicateUCP($body['extension']);
        $duplicate['user'] = $this->checkDuplicateUser($body['extension']);

        if ($duplicate['extension'] || $duplicate['device']) {
            return $response->withJson(array(
                'code' => 403,
                'data' => 'Extension already Exists. Cannot create extension / device ' . $body['extension']
            ));
        }

        if ($chan_drivers[$body['devicetype']] == 'chan_sip') {
            $tech = 'sip';
            $dial = 'SIP';
        } else {
            $tech = 'pjsip';
            $dial = 'PJSIP';
        }

        $devices = array(
            'id'            => $body['extension'],
            'tech'          => $tech,
            'dial'          => $dial . '/' . $body['extension'],
            'devicetype'    => 'fixed',
            'user'          => $body['extension'],
            'description'   => $body['displayname'],
            'emergency_cid' => (isset($body['emergencycid'])) ? $body['emergencycid'] : '',
            'hint_override' => (isset($body['hint_override'])) ? $body['hint_override'] : null
        );
        $sql = "insert into devices (id, tech, dial, devicetype, user, description, emergency_cid, hint_override) values (:id, :tech, :dial, :devicetype, :user, :description, :emergency_cid, :hint_override)";
        $stmt = $this->c->db->prepare($sql);
        foreach ($devices as $key => &$val) {
            $stmt->bindParam($key, $val);
        }
        $devicesResult = $stmt->execute();
        $result['devices'] = $devicesResult;

        $ampuser = array(
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/intercom/override",
                "value" => (isset($body['other']['intercom_override']) && in_array($body['other']['intercom_override'], array('force','ring','reject'))) ? $body['other']['intercom_override'] : 'reject'
            ),
            array(
                "key"   => "/AMPUSER/100/pinless",
                "value" => (isset($body['pinsets']['enabled']) && $body['pinsets']['enabled'] == 1) ? 'NOPASSWS' : ''
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/cwtone",
                "value" => (isset($body['advanced']['callwaiting_tone']) && $body['advanced']['callwaiting_tone'] == 1) ? 'enabled' : 'disabled'
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/ringtimer",
                "value" => (isset($body['advanced']['ringtimer'])) ? $body['advanced']['ringtimer'] : '0'
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/cfringtimer",
                "value" => (isset($body['advanced']['cfringtimer'])) ? $body['advanced']['cfringtimer'] : '0'
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/concurrency_limit",
                "value" => (isset($body['advanced']['concurrency_limit'])) ? $body['advanced']['concurrency_limit'] : '2'
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/cidname",
                "value" => $body['displayname']
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/cidnum",
                "value" =>  $body['extension']
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/answermode",
                "value" => (isset($body['advanced']['answermode']) && $body['advanced']['answermode'] == 1) ? 'intercom' : 'disabled'
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/intercom",
                "value" => (isset($body['advanced']['intercom']) && $body['advanced']['intercom'] == 1) ? 'enabled' : 'disabled'
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/recording/in/external",
                "value" => (isset($body['advanced']['recording_in_external']) && in_array($body['advanced']['recording_in_external'], array('dontcare','force','yes','no','never'))) ? $body['advanced']['recording_in_external'] : 'dontcare'
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/recording/out/external",
                "value" => (isset($body['advanced']['recording_out_external']) && in_array($body['advanced']['recording_out_external'], array('dontcare','force','yes','no','never'))) ? $body['advanced']['recording_out_external'] : 'dontcare'
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/recording/in/internal",
                "value" => (isset($body['advanced']['recording_in_internal']) && in_array($body['advanced']['recording_in_internal'], array('dontcare','force','yes','no','never'))) ? $body['advanced']['recording_in_internal'] : 'dontcare'
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/recording/out/internal",
                "value" => (isset($body['advanced']['recording_out_internal']) && in_array($body['advanced']['recording_out_internal'], array('dontcare','force','yes','no','never'))) ? $body['advanced']['recording_out_internal'] : 'dontcare'
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/recording/ondemand",
                "value" => (isset($body['advanced']['recording_ondemand']) && in_array($body['advanced']['recording_ondemand'], array('enabled','disabled','override'))) ? $body['advanced']['recording_out_internal'] : 'disabled'
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/recording/priority",
                "value" => (isset($body['advanced']['recording_priority']) && $body['advanced']['recording_priority'] == 1) ? $body['advanced']['recording_priority'] : '10'
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/dictate/enabled",
                "value" => (isset($body['advanced']['dictate_enabled']) && $body['advanced']['dictate_enabled'] == 1) ? "enabled" : "disabled"
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/dictate/format",
                "value" => (isset($body['advanced']['dictate_format']) && in_array($body['advanced']['dictate_format'], array('ogg','gsm','wav'))) ? $body['advanced']['dictate_format'] : "ogg"
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/dictate/from",
                "value" => "ZGljdGF0ZUBmcmVlcGJ4Lm9yZw=="
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/dictate/email",
                "value" => (isset($body['advanced']['dictate_email']) && $body['advanced']['dictate_email'] == 1) ? $body['advanced']['dictate_email'] : ""
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/queues/qnostate",
                "value" => "usestate"
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/voicemail",
                "value" => (isset($body['voicemail']['enabled']) && $body['voicemail']['enabled'] == 1) ? "default" : "novm"
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/hint",
                "value" => "SIP/".$body['extension']."&Custom =>DND".$body['extension'].",CustomPresence =>".$body['extension']
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/rvolume",
                "value" => ""
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/password",
                "value" => ""
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/noanswer",
                "value" => ""
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/recording",
                "value" => ""
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/outboundcid",
                "value" => (isset($body['outboundcid'])) ? $body['outboundcid'] : ''
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/emergency_cid",
                "value" => (isset($body['emergencycid'])) ? $body['emergencycid'] : ''
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/device",
                "value" => $body['extension']
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/followme/strategy",
                "value" => (isset($body['followme']['followmestrategy']) && in_array($body['followme']['followmestrategy'], $followMeStrategies)) ? $body['followme']['followmestrategy'] : 'ringallv2-prim',
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/followme/prering",
                "value" => (isset($body['followme']['pre_ring'])) ? $body['followme']['pre_ring'] : 7,
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/followme/grptime",
                "value" => (isset($body['followme']['grptime'])) ? $body['followme']['grptime'] : '20',
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/followme/grplist",
                "value" => (isset($body['followme']['grplist']) && count($body['followme']['grplist']) > 0) ? implode('-', $body['followme']['grplist']) : $body['extension'],
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/followme/annmsg",
                "value" => (isset($body['followme']['announcement_message'])) ? $this->getDestinationIdByType('announcement', $body['followme']['announcement_message']) : "",
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/followme/remotealertmsg",
                "value" => (isset($body['followme']['remote_announce'])) ? $this->getDestinationIdByType('announcement', $body['followme']['remote_announce']) : "",
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/followme/toolatemsg",
                "value" => (isset($body['followme']['too_late_announce'])) ? $this->getDestinationIdByType('announcement', $body['followme']['too_late_announce']) : "",
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/followme/postdest",
                "value" => (isset($body['followme']['post_destination'])) ? $this->matchVariables($destinationTypes[$body['followme']['post_destination_type']], array('destination' => $this->getDestinationIdByType($body['followme']['post_destination_type'], $body['followme']['post_destination']))) : "",
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/followme/ringing",
                "value" => (isset($body['followme']['ringing'])) ? $body['followme']['ringing'] : 'Ring',
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/followme/grpconf",
                "value" => "ENABLED"
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/followme/ddial",
                "value" => (isset($body['followme']['enabled']) && $body['followme']['enabled'] == 1) ? "DIRECT" : 'EXTENSION',
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/followme/changecid",
                "value" => (isset($body['followme']['changecid']) && in_array($body['followme']['changecid'], array('default','fixed','extern','did','forcedid'))) ? $body['followme']['changecid'] : 'default',
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/followme/fixedcid",
                "value" => (isset($body['followme']['fixedcid']) && ($body['followme']['changecid'] == "default" || $body['followme']['changecid'] == '')) ? "" : $body['followme']['fixedcid'],
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/language",
                "value" => ""
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/followme/grppre",
                "value" => (isset($body['followme']['grppre'])) ? $body['followme']['grppre'] : '',
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/followme/dring",
                "value" => (isset($body['followme']['dring'])) ? $body['followme']['dring'] : '',
            ),
            array(
                "key" => "/AMPUSER/". $body['extension'] ."/followme/rvolume",
                "value" => (isset($body['followme']['rvolume'])) ? $body['followme']['rvolume'] : '',
            ),
            array(
                "key" => "/DEVICE/".$body['extension']."/user",
                "value" => $body['extension']
            ),
            array(
                "key" => "/DEVICE/".$body['extension']."/tech",
                "value" => $tech
            ),
            array(
                "key" => "/DEVICE/100/dial",
                "value" => $dial
            ),
            array(
                "key" => "/DEVICE/".$body['extension']."/type",
                "value" => "fixed"
            ),
            array(
                "key" => "/DEVICE/".$body['extension']."/default_user",
                "value" => $body['extension']
            ),
            array(
                "key" => "/CW/".$body['extension'],
                "value" => (isset($body['advanced']['callwaiting_enabled']) && $body['advanced']['callwaiting_enabled'] == 1) ? 'ENABLED' : 'DISABLED'
            ),
        );
        foreach ($ampuser as $setting) {
            try {
                $astDBSQL = "insert into astdb (key, value) values (:key, :value)";
                $stmt = $this->c->sqlite->prepare($astDBSQL);
                foreach ($setting as $key => &$val) {
                    $stmt->bindParam($key, $val);
                }
                $astDBResult = $stmt->execute();
                $result['astdb'] = $astDBResult;
            } catch (PDOException $e) {
                $result['astdb'] = $e->getMessage();
            } catch (Exception $e) {
                $result['astdb'] = $e->getMessage();
            }
        }

        if($body['followme']['enabled'] == "1"){
            $findmefollow = array(
                'grpnum'            =>  $body['extension'],
                'strategy'          =>  (isset($body['followme']['followmestrategy']) && in_array($body['followme']['followmestrategy'], $followMeStrategies)) ? $body['followme']['followmestrategy'] : 'ringallv2-prim',
                'grptime'           =>  (isset($body['followme']['grptime'])) ? $body['followme']['grptime'] : '20',
                'grppre'            =>  (isset($body['followme']['grppre'])) ? $body['followme']['grppre'] : '',
                'grplist'           =>  (isset($body['followme']['grplist']) && count($body['followme']['grplist']) > 0) ? implode('-', $body['followme']['grplist']) : $body['extension'],
                'annmsg_id'         =>  (isset($body['followme']['announcement_message'])) ? $this->getDestinationIdByType('announcement', $body['followme']['announcement_message']) : null,
                'postdest'          =>  (isset($body['followme']['post_destination'])) ? $this->matchVariables($destinationTypes[$body['followme']['post_destination_type']], array('destination' => $this->getDestinationIdByType($body['followme']['post_destination_type'], $body['followme']['post_destination']))) : null,
                'dring'             =>  (isset($body['followme']['dring'])) ? $body['followme']['dring'] : '',
                'rvolume'           =>  (isset($body['followme']['rvolume'])) ? $body['followme']['rvolume'] : '',
                'remotealert_id'    =>  (isset($body['followme']['remote_announce'])) ? $this->getDestinationIdByType('announcement', $body['followme']['remote_announce']) : null,
                'needsconf'         =>  (isset($body['followme']['needsconf']) && $body['followme']['needsconf'] == 1) ? 'CHECKED' : '',
                'toolate_id'        =>  (isset($body['followme']['too_late_announce'])) ? $this->getDestinationIdByType('announcement', $body['followme']['too_late_announce']) : null,
                'pre_ring'          =>  (isset($body['followme']['pre_ring'])) ? $body['followme']['pre_ring'] : 7,
                'ringing'           =>  (isset($body['followme']['ringing'])) ? $body['followme']['ringing'] : 'Ring',
                'calendar_enable'   =>  (isset($body['followme']['calendar_enable'])) ? $body['followme']['calendar_enable'] : '',
                'calendar_id'       =>  (isset($body['followme']['calendar_id'])) ? $body['followme']['calendar_id'] : '',
                'calendar_group_id' =>  (isset($body['followme']['calendar_group_id'])) ? $body['followme']['calendar_group_id'] : '',
                'calendar_match'    =>  (isset($body['followme']['calendar_match'])) ? $body['followme']['calendar_match'] : 'yes',
            );

            if (!$duplicate['followme']) {
                $sql = "insert into findmefollow (grpnum, strategy, grptime, grppre, grplist, annmsg_id, postdest, dring, rvolume, remotealert_id, needsconf, toolate_id, pre_ring, ringing, calendar_enable, calendar_id, calendar_group_id, calendar_match) values (:grpnum, :strategy, :grptime, :grppre, :grplist, :annmsg_id, :postdest, :dring, :rvolume, :remotealert_id, :needsconf, :toolate_id, :pre_ring, :ringing, :calendar_enable, :calendar_id, :calendar_group_id, :calendar_match)";
                $stmt = $this->c->db->prepare($sql);
                foreach ($findmefollow as $key => &$val) {
                    $stmt->bindParam($key, $val);
                }
                $findmefollowResult = $stmt->execute();
                $result['findmefollow'] = $findmefollowResult;
            } else {
                $result['findmefollow'] = 'Cannot create Follow Me for Extension ' . $body['extension'] . '. Configuration already exists.';
            }
        }

        if (isset($body['incomingdid']) && $body['incomingdid'] != '') {
            $duplicate['incoming'] = $this->checkDuplicateInboundRouteDID($body['incomingdid']);
            $incoming = array(
                'cidnum'            =>  (isset($body['cidnum'])) ? $body['cidnum'] : '',
                'extension'         =>  $body['incomingdid'],
                'destination'       =>  'from-did,' . $body['extension'] . ',1',
                'privacyman'        =>  0,
                'alertinfo'         =>  (isset($body['alertinfo'])) ? $body['alertinfo'] : '',
                'ringing'           =>  (isset($body['ringing'])) ? $body['ringing'] : '',
                'fanswer'           =>  (isset($body['fanswer'])) ? $body['fanswer'] : '',
                'mohclass'          =>  (isset($body['mohclass'])) ? $body['mohclass'] : 'default',
                'description'       =>  $body['incomingdid'],
                'grppre'            =>  (isset($body['grppre'])) ? $body['grppre'] : '',
                'delay_answer'      =>  (isset($body['delay_answer'])) ? $body['delay_answer'] : 0,
                'pricid'            =>  (isset($body['pricid'])) ? $body['pricid'] : '',
                'pmmaxretries'      =>  (isset($body['pmmaxretries'])) ? $body['pmmaxretries'] : '',
                'pmminlength'       =>  (isset($body['pmminlength'])) ? $body['pmminlength'] : '',
                'reversal'          =>  (isset($body['reversal'])) ? $body['reversal'] : '',
                'rvolume'           =>  (isset($body['rvolume'])) ? $body['rvolume'] : '',
                'indication_zone'   =>  (isset($body['indication_zone'])) ? $body['indication_zone'] : 'default'
            );

            if (!$duplicate['incoming']) {
                $sql = "insert into incoming (cidnum, extension, destination, privacyman, alertinfo, ringing, fanswer, mohclass, description, grppre, delay_answer, pricid, pmmaxretries, pmminlength, reversal, rvolume, indication_zone) values (:cidnum, :extension, :destination, :privacyman, :alertinfo, :ringing, :fanswer, :mohclass, :description, :grppre, :delay_answer, :pricid, :pmmaxretries, :pmminlength, :reversal, :rvolume, :indication_zone)";
                $stmt = $this->c->db->prepare($sql);
                foreach ($incoming as $key => &$val) {
                    $stmt->bindParam($key, $val);
                }
                $incomingResult = $stmt->execute();
                $result['incoming'] = $incomingResult;
            } else {
                $result['incoming'] = 'Cannot create Incoming DID Route for Extension ' . $body['extension'] . '. Configuration already exists.';
            }
        }

        if ($tech == 'sip') {
            $settings = array(
                array(
                    "id" => $body['extension'],
                    "keyword" => 'dial',
                    "data" => 'SIP/' . $body['extension'],
                    "flags" => 27
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => 'account',
                    "data" => $body['extension'],
                    "flags" => 34
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => 'accountcode',
                    "data" => (isset($body['accountcode'])) ? $body['accountcode'] : '',
                    "flags" => 28
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => 'allow',
                    "data" => (isset($body['allow'])) ? $body['allow'] : '',
                    "flags" => 26
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => 'avpf',
                    "data" => (isset($body['avpf'])) ? $body['avpf'] : "no",
                    "flags" => 17
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "callerid",
                    "data" => $body['displayname'] . ' <' . $body['extension'] . '>',
                    "flags" => 35
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "canreinvite",
                    "data" => (isset($body['canreinvite'])) ? $body['canreinvite'] : "no",
                    "flags" => 4
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "context",
                    "data" => (isset($body['context'])) ? $body['context'] : "from-internal",
                    "flags" => 5
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "defaultuser",
                    "data" => (isset($body['defaultuser'])) ? $body['defaultuser'] : '',
                    "flags" => 7
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "deny",
                    "data" => (isset($body['deny'])) ? $body['deny'] : "0.0.0.0/0.0.0.0",
                    "flags" => 30
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "disallow",
                    "data" => (isset($body['disallow'])) ? $body['disallow'] : '',
                    "flags" => 25
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "dtmfmode",
                    "data" => (isset($body['dtmfmode'])) ? $body['dtmfmode'] : "rfc2833",
                    "flags" => 3
                ),
                "encryption" => array(
                    "id" => $body['extension'],
                    "keyword" => "encryption",
                    "data" => (isset($body['encryption'])) ? $body['encryption'] : "no",
                    "flags" => 21
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "force_avp",
                    "data" => (isset($body['force_avp'])) ? $body['force_avp'] : "no",
                    "flags" => 18
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "host",
                    "data" => (isset($body['host'])) ? $body['host'] : "dynamic",
                    "flags" => 6
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "icesupport",
                    "data" => (isset($body['icesupport'])) ? $body['icesupport'] : "no",
                    "flags" => 19
                ),
                "mailbox" => array(
                    "id" => $body['extension'],
                    "keyword" => "mailbox",
                    "data" => $body['extension'] . '@device',
                    "flags" => 29
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "namedcallgroup",
                    "data" => (isset($body['namedcallgroup'])) ? $body['namedcallgroup'] : "",
                    "flags" => 23
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "namedpickupgroup",
                    "data" => (isset($body['namedpickupgroup'])) ? $body['namedpickupgroup'] : "",
                    "flags" => 24
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "nat",
                    "data" => (isset($body['nat'])) ? $body['nat'] : "yes",
                    "flags" => 12
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "permit",
                    "data" => (isset($body['permit'])) ? $body['permit'] : "0.0.0.0/0.0.0.0",
                    "flags" => 31
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "port",
                    "data" => (isset($body['port'])) ? $body['port'] : "5060",
                    "flags" => 13
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "qualify",
                    "data" => (isset($body['qualify'])) ? $body['qualify'] : "yes",
                    "flags" => 14
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "qualifyfreq",
                    "data" => (isset($body['qualifyfreq'])) ? $body['qualifyfreq'] : "60",
                    "flags" => 15
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "rtcp_mux",
                    "data" => (isset($body['rtcp_mux'])) ? $body['rtcp_mux'] : "no",
                    "flags" => 20
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "secret",
                    "data" => $body['secret'],
                    "flags" => 2
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "secret_origional",
                    "data" => $body['secret'],
                    "flags" => 32
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "sendrpid",
                    "data" => (isset($body['sendrpid'])) ? $body['sendrpid'] : "pai",
                    "flags" => 9
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "sessiontimers",
                    "data" => (isset($body['sessiontimers'])) ? $body['sessiontimers'] : "accept",
                    "flags" => 11
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "sipdriver",
                    "data" => $chan_drivers[$body['devicetype']],
                    "flags" => 33
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "transport",
                    "data" => "udp,tcp,tls",
                    "flags" => 16
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "trustrpid",
                    "data" => (isset($body['trustrpid'])) ? $body['trustrpid'] : "yes",
                    "flags" => 8
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "type",
                    "data" => (isset($body['type'])) ? $body['type'] : "friend",
                    "flags" => 10
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "videosupport",
                    "data" => (isset($body['videosupport'])) ? $body['videosupport'] : "inherit",
                    "flags" => 22
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "vmexten",
                    "data" => (isset($body['vmexten'])) ? $body['vmexten'] : '',
                    "flags" => 30
                ),
            );
        }
        elseif ($tech == 'pjsip') {
            $settings = array(
                array(
                    "id" => $body['extension'],
                    "keyword" => 'account',
                    "data" => $body['extension'],
                    "flags" => 48
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => 'accountcode',
                    "data" => (isset($body['accountcode'])) ? $body['accountcode'] : '',
                    "flags" => 21
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => 'aggregate_mwi',
                    "data" => (isset($body['aggregate_mwi'])) ? $body['aggregate_mwi'] : 'yes',
                    "flags" => 28
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => 'allow',
                    "data" => (isset($body['allow'])) ? $body['allow'] : '',
                    "flags" => 17
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => 'avpf',
                    "data" => (isset($body['avpf'])) ? $body['avpf'] : "no",
                    "flags" => 11
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => 'bundle',
                    "data" => (isset($body['bundle'])) ? $body['bundle'] : "no",
                    "flags" => 29
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "callerid",
                    "data" => $body['displayname'] . ' <' . $body['extension'] . '>',
                    "flags" => 49
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "context",
                    "data" => (isset($body['context'])) ? $body['context'] : "from-internal",
                    "flags" => 4
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "defaultuser",
                    "data" => (isset($body['defaultuser'])) ? $body['defaultuser'] : '',
                    "flags" => 5
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "device_state_busy_at",
                    "data" => (isset($body['device_state_busy_at'])) ? $body['device_state_busy_at'] : 0,
                    "flags" => 38
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => 'dial',
                    "data" => 'PJSIP/' . $body['extension'],
                    "flags" => 18
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => 'direct_media',
                    "data" => (isset($body['direct_media'])) ? $body['direct_media'] : 'yes',
                    "flags" => 35
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "disallow",
                    "data" => (isset($body['disallow'])) ? $body['disallow'] : '',
                    "flags" => 16
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "dtmfmode",
                    "data" => (isset($body['dtmfmode'])) ? $body['dtmfmode'] : "rfc2833",
                    "flags" => 3
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "force_rport",
                    "data" => (isset($body['force_rport'])) ? $body['force_rport'] : "yes",
                    "flags" => 26
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "icesupport",
                    "data" => (isset($body['icesupport'])) ? $body['icesupport'] : "no",
                    "flags" => 12
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "mailbox",
                    "data" => $body['extension'] . '@device',
                    "flags" => 19
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "match",
                    "data" => (isset($body['match'])) ? $body['match'] : "",
                    "flags" => 39
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "max_audio_streams",
                    "data" => (isset($body['max_audio_streams'])) ? $body['max_audio_streams'] : "1",
                    "flags" => 20
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "max_contacts",
                    "data" => (isset($body['max_contacts'])) ? $body['max_contacts'] : "1",
                    "flags" => 22
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "max_video_streams",
                    "data" => (isset($body['max_video_streams'])) ? $body['max_video_streams'] : "1",
                    "flags" => 31
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "maximum_expiration",
                    "data" => (isset($body['maximum_expiration'])) ? $body['maximum_expiration'] : "7200",
                    "flags" => 40
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "media_encryption",
                    "data" => (isset($body['media_encryption'])) ? $body['media_encryption'] : "no",
                    "flags" => 32
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "media_encryption_optimistic",
                    "data" => (isset($body['media_encryption_optimistic'])) ? $body['media_encryption_optimistic'] : "no",
                    "flags" => 36
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "media_use_received_transport",
                    "data" => (isset($body['media_use_received_transport'])) ? $body['media_use_received_transport'] : "no",
                    "flags" => 23
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "message_context",
                    "data" => (isset($body['message_context'])) ? $body['message_context'] : "",
                    "flags" => 45
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "minimum_expiration",
                    "data" => (isset($body['minimum_expiration'])) ? $body['minimum_expiration'] : 60,
                    "flags" => 41
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "mwi_subscription",
                    "data" => (isset($body['mwi_subscription'])) ? $body['mwi_subscription'] : "auto",
                    "flags" => 27
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "namedcallgroup",
                    "data" => (isset($body['namedcallgroup'])) ? $body['namedcallgroup'] : "",
                    "flags" => 14
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "namedpickupgroup",
                    "data" => (isset($body['namedpickupgroup'])) ? $body['namedpickupgroup'] : "",
                    "flags" => 15
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "outbound_proxy",
                    "data" => (isset($body['outbound_proxy'])) ? $body['outbound_proxy'] : "",
                    "flags" => 44
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "qualifyfreq",
                    "data" => (isset($body['qualifyfreq'])) ? $body['qualifyfreq'] : 60,
                    "flags" => 9
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "refer_blind_progress",
                    "data" => (isset($body['refer_blind_progress'])) ? $body['refer_blind_progress'] : "yes",
                    "flags" => 37
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "rewrite_contact",
                    "data" => (isset($body['rewrite_contact'])) ? $body['rewrite_contact'] : "yes",
                    "flags" => 25
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "rtcp_mux",
                    "data" => (isset($body['rtcp_mux'])) ? $body['rtcp_mux'] : "no",
                    "flags" => 13
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "rtp_symmetric",
                    "data" => (isset($body['rtp_symmetric'])) ? $body['rtp_symmetric'] : "yes",
                    "flags" => 24
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "rtp_timeout",
                    "data" => (isset($body['rtp_timeout'])) ? $body['rtp_timeout'] : 0,
                    "flags" => 42
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "rtp_timeout_hold",
                    "data" => (isset($body['rtp_timeout_hold'])) ? $body['rtp_timeout_hold'] : 0,
                    "flags" => 43
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "secret",
                    "data" => $body['secret'],
                    "flags" => 2
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "secret_origional",
                    "data" => $body['secret'],
                    "flags" => 46
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "send_connected_line",
                    "data" => (isset($body['send_connected_line'])) ? $body['send_connected_line'] : "yes",
                    "flags" => 7
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "sendrpid",
                    "data" => (isset($body['sendrpid'])) ? $body['sendrpid'] : "pai",
                    "flags" => 8
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "sipdriver",
                    "data" => $chan_drivers[$body['devicetype']],
                    "flags" => 47
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "timers",
                    "data" => (isset($body['timers'])) ? $body['timers'] : "yes",
                    "flags" => 33
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "timers_min_se",
                    "data" => (isset($body['timers_min_se'])) ? $body['timers_min_se'] : 90,
                    "flags" => 34
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "transport",
                    "data" => "",
                    "flags" => 10
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "trustrpid",
                    "data" => (isset($body['trustrpid'])) ? $body['trustrpid'] : "yes",
                    "flags" => 6
                ),
                array(
                    "id" => $body['extension'],
                    "keyword" => "vmexten",
                    "data" => (isset($body['vmexten'])) ? $body['vmexten'] : '',
                    "flags" => 20
                ),
            );
        }

        foreach ($settings as $setting) {
            $sql = "insert into sip (id, keyword, data, flags) values (:id, :keyword, :data, :flags)";
            $stmt = $this->c->db->prepare($sql);
            foreach ($setting as $key => &$val) {
                $stmt->bindParam($key, $val);
            }
            $sipResult = $stmt->execute();
            $result['sip'] = $sipResult;
        }

        if (isset($body['create_user']) && $body['create_user'] == "1") {
            $userman_users = array(
                'auth'              =>  (isset($body['auth'])) ? $body['auth'] : 1,
                'authid'            =>  (isset($body['authid'])) ? $body['authid'] : null,
                'username'          =>  $body['extension'],
                'description'       =>  'Autogenerated user on new device creation via API',
                'password'          =>  '$2a$08$QR/ZjchVw4Mgsyoz4okE9ulZxAWhpeKsvQwuWLoH7e70GIkZQ4jUC', //Todo check freepbx password encryption
                'default_extension' =>  $body['extension'],
                'primary_group'     =>  (isset($body['primary_group'])) ? $body['primary_group'] : null,
                'permissions'       =>  (isset($body['permissions'])) ? $body['permissions'] : null,
                'fname'             =>  (isset($body['fname'])) ? $body['fname'] : null,
                'lname'             =>  (isset($body['lname'])) ? $body['lname'] : null,
                'displayname'       =>  $body['displayname'],
                'title'             =>  (isset($body['lname'])) ? $body['lname'] : null,
                'company'           =>  (isset($body['company'])) ? $body['company'] : null,
                'department'        =>  (isset($body['department'])) ? $body['department'] : null,
                'language'          =>  (isset($body['language'])) ? $body['language'] : null,
                'timezone'          =>  (isset($body['timezone'])) ? $body['timezone'] : null,
                'dateformat'        =>  (isset($body['dateformat'])) ? $body['dateformat'] : null,
                'timeformat'        =>  (isset($body['timeformat'])) ? $body['timeformat'] : null,
                'datetimeformat'    =>  (isset($body['datetimeformat'])) ? $body['datetimeformat'] : null,
                'email'             =>  (isset($body['email'])) ? $body['email'] : null,
                'cell'              =>  (isset($body['cell'])) ? $body['cell'] : null,
                'work'              =>  (isset($body['work'])) ? $body['work'] : null,
                'home'              =>  (isset($body['home'])) ? $body['home'] : null,
                'fax'               =>  (isset($body['fax'])) ? $body['fax'] : null,
            );

            if (!$duplicate['ucp']) {
                $sql = "insert into userman_users (auth, authid, username, description, password, default_extension, primary_group, permissions, fname, lname, displayname, title, company, department, language, timezone, dateformat, timeformat, datetimeformat, email, cell, work, home, fax) values (:auth, :authid, :username, :description, :password, :default_extension, :primary_group, :permissions, :fname, :lname, :displayname, :title, :company, :department, :language, :timezone, :dateformat, :timeformat, :datetimeformat, :email, :cell, :work, :home, :fax)";
                $stmt = $this->c->db->prepare($sql);
                foreach ($userman_users as $key => &$val) {
                    $stmt->bindParam($key, $val);
                }
                $userman_usersResult = $stmt->execute();
                $result['userman_users'] = $userman_usersResult;
            } else {
                $result['userman_users'] = 'Cannot create UCP User for Extension ' . $body['extension'] . '. Configuration already exists.';
            }
        }

        $users = array(
            'extension'         =>  $body['extension'],
            'password'          =>  '',
            'name'              =>  $body['displayname'],
            'voicemail'         =>  (isset($body['voicemail']['enabled']) && $body['voicemail']['enabled'] == "1") ? "default" : 'novm',
            'ringtimer'         =>  (isset($body['ringtimer'])) ? $body['ringtimer'] : 0,
            'noanswer'          =>  (isset($body['noanswer'])) ? $body['noanswer'] : '',
            'recording'         =>  (isset($body['recording'])) ? $body['recording'] : '',
            'outboundcid'       =>  (isset($body['outboundcid'])) ? $body['outboundcid'] : '',
            'sipname'           =>  (isset($body['sipname'])) ? $body['sipname'] : '',
            'noanswer_cid'      =>  (isset($body['noanswer_cid'])) ? $body['noanswer_cid'] : '',
            'busy_cid'          =>  (isset($body['busy_cid'])) ? $body['busy_cid'] : '',
            'chanunavail_cid'   =>  (isset($body['chanunavail_cid'])) ? $body['chanunavail_cid'] : '',
            'noanswer_dest'     =>  (isset($body['noanswer_dest'])) ? $body['noanswer_dest'] : '',
            'busy_dest'         =>  (isset($body['busy_dest'])) ? $body['busy_dest'] : '',
            'chanunavail_dest'  =>  (isset($body['chanunavail_dest'])) ? $body['chanunavail_dest'] : '',
            'mohclass'          =>  (isset($body['mohclass'])) ? $body['mohclass'] : 'default',
        );

        if (!$duplicate['user']) {
            $sql = "insert into users (extension, password, name, voicemail, ringtimer, noanswer, recording, outboundcid, sipname, noanswer_cid, busy_cid, chanunavail_cid, noanswer_dest, busy_dest, chanunavail_dest, mohclass) values (:extension, :password, :name, :voicemail, :ringtimer, :noanswer, :recording, :outboundcid, :sipname, :noanswer_cid, :busy_cid, :chanunavail_cid, :noanswer_dest, :busy_dest, :chanunavail_dest, :mohclass)";
            $stmt = $this->c->db->prepare($sql);
            foreach ($users as $key => &$val) {
                $stmt->bindParam($key, $val);
            }
            $usersResult = $stmt->execute();
            $result['users'] = $usersResult;
        } else {
            $result['users'] = 'Cannot create User for Extension ' . $body['extension'] . '. Configuration already exists.';
        }

        return $response->withJson(array(
            'code' => 200,
            'data' => array('extension' => $body['extension'], 'result' => $result)
        ));
    }
    //End Extensions

    //Trunks
    public function getAllSIPTrunks($request, $response){
        $sql = "SELECT * FROM trunks;";
        $stmt = $this->c->db->query($sql);
        $sipExtensions = $stmt->fetchAll();

        return $response->withJson(array(
            'code' => 200,
            'data' => $sipExtensions
        ));
    }
    public function createSIPTrunk($request, $response){
        global $chan_drivers;
        $body = $request->getParsedBody();

        if (!isset($body['trunk']['tech']) || $body['trunk']['tech'] == '' || !array_key_exists($body['trunk']['tech'], $chan_drivers)) {
            return $response->withJson(array(
                'code' => 501,
                'data' => 'Technology not defined. Possible values (SIP or PJSIP)'
            ));
        }
        elseif (!isset($body['trunk']['name']) || $body['trunk']['name'] == '') {
            return $response->withJson(array(
                'code' => 501,
                'data' => 'Trunk Name not defined.'
            ));
        }
        elseif (!isset($body['peercontext']) || !count($body['peercontext']) > 0) {
            return $response->withJson(array(
                'code' => 501,
                'data' => 'Peer Context not defined.'
            ));
        }
        elseif (!isset($body['peercontext']['name']) || $body['peercontext']['name'] == '') {
            return $response->withJson(array(
                'code' => 501,
                'data' => 'Peer Context not defined.'
            ));
        }
        elseif (isset($body['trunk']['name']) && $this->checkDuplicateTrunk($body['trunk']['name'])) {
            return $response->withJson(array(
                'code' => 401,
                'data' => 'Trunk name must be Unique'
            ));
        }
        elseif (isset($body['usercontext']['name']) && $this->checkDuplicateUserContext($body['usercontext']['name']) && $body['trunk']['tech'] != 'pjsip') {
            return $response->withJson(array(
                'code' => 401,
                'data' => 'User Context must be Unique'
            ));
        }
        elseif (isset($body['peercontext']['name']) && $this->checkDuplicatePeerContext($body['peercontext']['name']) && $body['trunk']['tech'] != 'pjsip') {
            return $response->withJson(array(
                'code' => 401,
                'data' => 'Peer Context must be Unique'
            ));
        }
        elseif ($body['trunk']['tech'] == 'pjsip' && !isset($body['pjsip'])) {
            return $response->withJson(array(
                'code' => 501,
                'data' => 'PJSIP Settings not found.'
            ));
        }
        elseif ($body['trunk']['tech'] == 'pjsip' && (!isset($body['pjsip']['sip_server']) || $body['pjsip']['sip_server'] == '')) {
            return $response->withJson(array(
                'code' => 501,
                'data' => 'PJSIP Server Address cannot be Empty or is not defined.'
            ));
        }

        $trunkId = ($this->getTrunkId()) ? $this->getTrunkId() + 1 : 1;

        $trunk = array(
            'trunkid'       => $trunkId,
            'tech'          => $body['trunk']['tech'],
            'name'          => trim($body['trunk']['name']),
            'channelid'     => (isset($body['peercontext']['name']) && $body['trunk']['tech'] == 'sip') ? $body['peercontext']['name'] : trim($body['trunk']['name']),
            'outcid'        => (isset($body['trunk']['outcid'])) ? $body['trunk']['outcid'] : "",
            'keepcid'       => (isset($body['trunk']['keepcid'])) ? $body['trunk']['keepcid'] : "off",
            'maxchans'      => (isset($body['trunk']['maxchans'])) ? $body['trunk']['maxchans'] : "",
            'failscript'    => (isset($body['trunk']['failscript'])) ? $body['trunk']['failscript'] : "",
            'dialoutprefix' => (isset($body['trunk']['dialoutprefix'])) ? $body['trunk']['dialoutprefix'] : "",
            'usercontext'   => (isset($body['usercontext']['name']) && $body['trunk']['tech'] != 'pjsip') ? $body['usercontext']['name'] : "",
            'provider'      => (isset($body['trunk']['provider'])) ? $body['trunk']['provider'] : "",
            'disabled'      => (isset($body['trunk']['disabled'])) ? $body['trunk']['disabled'] : "off",
            'continue'      => (isset($body['trunk']['continue'])) ? $body['trunk']['continue'] : "off",
        );

        $trunkSQL = "insert into trunks (trunkid, tech, channelid, name, outcid, keepcid, maxchans, failscript, dialoutprefix, usercontext, provider, disabled, `continue`) values (:trunkid, :tech, :channelid, :name, :outcid, :keepcid, :maxchans, :failscript, :dialoutprefix, :usercontext, :provider, :disabled, :continue)";
        $stmt = $this->c->db->prepare($trunkSQL);
        foreach ($trunk as $key => &$val) {
            $stmt->bindParam($key, $val);
        }
        $trunkResult = $stmt->execute();
        $result['trunk'] = $trunkResult;

        if (isset($body['dialpatterns']) && count($body['dialpatterns']) > 0) {
            foreach ($body['dialpatterns'] as $dialPattern) {
                $dialPatterns[] = array(
                    'trunkid'               => $trunkId,
                    'match_pattern_prefix'  => (isset($dialPattern['match_pattern_prefix'])) ? $dialPattern['match_pattern_prefix'] : "",
                    'match_pattern_pass'    => (isset($dialPattern['match_pattern_pass'])) ? $dialPattern['match_pattern_prefix'] : "",
                    'prepend_digits'        => (isset($dialPattern['prepend_digits'])) ? $dialPattern['prepend_digits'] : "",
                    'seq'                   => (isset($dialPattern['seq'])) ? $dialPattern['seq'] : 0,
                );
            }

            foreach ($dialPatterns as $setting) {
                $dialPatternSQL = "insert into trunk_dialpatterns (trunkid, match_pattern_prefix, match_pattern_pass, prepend_digits, seq) values (:trunkid, :match_pattern_prefix, :match_pattern_pass, :prepend_digits, :seq)";
                $stmt = $this->c->db->prepare($dialPatternSQL);
                foreach ($setting as $key => &$val) {
                    $stmt->bindParam($key, $val);
                }
                $dialPatternResult = $stmt->execute();
                $result['dialplan_patterns'] = $dialPatternResult;
            }
        }

        if ($body['trunk']['tech'] == 'sip') {
            if (isset($body['peercontext']) && count($body['peercontext']) > 0) {
                $peerContextCount = 1;
                foreach ($body['peercontext'] as $key => &$val) {
                    $sip[] = array(
                        'id' => 'tr-peer-' . $trunkId,
                        'keyword' => ($key == 'name') ? 'account' : $key,
                        'data' => $val,
                        'flags' => $peerContextCount
                    );
                    $peerContextCount++;
                }
            }

            if (isset($body['usercontext']) && count($body['usercontext']) > 0) {
                $userContextCount = 1;
                foreach ($body['usercontext'] as $key => &$val) {
                    $sip[] = array(
                        'id' => 'tr-user-' . $trunkId,
                        'keyword' => ($key == 'name') ? 'account' : $key,
                        'data' => $val,
                        'flags' => $userContextCount
                    );
                    $userContextCount++;
                }
            }

            if (isset($body['registerstring']) || $body['registerstring'] != '') {
                $sip[] = array(
                    'id' => 'tr-reg-' . $trunkId,
                    'keyword' => 'register',
                    'data' => $body['registerstring'],
                    'flags' => 0
                );
            }

            foreach ($sip as $setting) {
                $sql = "insert into sip (id, keyword, data, flags) values (:id, :keyword, :data, :flags)";
                $stmt = $this->c->db->prepare($sql);
                foreach ($setting as $key => &$val) {
                    $stmt->bindParam($key, $val);
                }
                $sipResult = $stmt->execute();
                $result['sip'] = $sipResult;
            }
        } else {
            $pjsip = array(
                array("id" => $trunkId, "keyword" => "aor_contact", "data" => (isset($body["pjsip"]["aor_contact"])) ? $body["pjsip"]["aor_contact"] : "", "flags" => 0),
                array("id" => $trunkId, "keyword" => "aors", "data" => (isset($body["pjsip"]["aors"])) ? $body["pjsip"]["aors"] : "", "flags" => 0),
                array("id" => $trunkId, "keyword" => "auth_rejection_permanent", "data" => (isset($body["pjsip"]["auth_rejection_permanent"])) ? $body["pjsip"]["auth_rejection_permanent"] : "off", "flags" => 0),
                array("id" => $trunkId, "keyword" => "authentication", "data" => (isset($body["pjsip"]["authentication"])) ? $body["pjsip"]["authentication"] : "both", "flags" => 0),
                array("id" => $trunkId, "keyword" => "client_uri", "data" => (isset($body["pjsip"]["client_uri"])) ? $body["pjsip"]["client_uri"] : "", "flags" => 0),
                array("id" => $trunkId, "keyword" => "codecs", "data" => (isset($body["pjsip"]["codecs"])) ? $body["pjsip"]["codecs"] : "ulaw,alaw,gsm,g726,g722", "flags" => 0),
                array("id" => $trunkId, "keyword" => "contact_user", "data" => (isset($body["pjsip"]["contact_user"])) ? $body["pjsip"]["contact_user"] : "", "flags" => 0),
                array("id" => $trunkId, "keyword" => "context", "data" => (isset($body["pjsip"]["context"])) ? $body["pjsip"]["context"] : "from-pstn", "flags" => 0),
                array("id" => $trunkId, "keyword" => "dialopts", "data" => (isset($body["pjsip"]["dialopts"])) ? $body["pjsip"]["dialopts"] : "", "flags" => 0),
                array("id" => $trunkId, "keyword" => "dialoutopts_cb", "data" => (isset($body["pjsip"]["dialoutopts_cb"])) ? $body["pjsip"]["dialoutopts_cb"] : "sys", "flags" => 0),
                array("id" => $trunkId, "keyword" => "direct_media", "data" => (isset($body["pjsip"]["direct_media"])) ? $body["pjsip"]["direct_media"] : "no", "flags" => 0),
                array("id" => $trunkId, "keyword" => "disabletrunk", "data" => (isset($body["pjsip"]["disabletrunk"])) ? $body["pjsip"]["disabletrunk"] : "off", "flags" => 0),
                array("id" => $trunkId, "keyword" => "dtmfmode", "data" => (isset($body["pjsip"]["dtmfmode"])) ? $body["pjsip"]["dtmfmode"] : "auto", "flags" => 0),
                array("id" => $trunkId, "keyword" => "expiration", "data" => (isset($body["pjsip"]["expiration"])) ? $body["pjsip"]["expiration"] : "3600", "flags" => 0),
                array("id" => $trunkId, "keyword" => "extdisplay", "data" => (isset($body["pjsip"]["extdisplay"])) ? $body["pjsip"]["extdisplay"] : "OUT_1", "flags" => 0),
                array("id" => $trunkId, "keyword" => "failtrunk_enable", "data" => (isset($body["pjsip"]["failtrunk_enable"])) ? $body["pjsip"]["failtrunk_enable"] : "0", "flags" => 0),
                array("id" => $trunkId, "keyword" => "fatal_retry_interval", "data" => (isset($body["pjsip"]["fatal_retry_interval"])) ? $body["pjsip"]["fatal_retry_interval"] : "30", "flags" => 0),
                array("id" => $trunkId, "keyword" => "fax_detect", "data" => (isset($body["pjsip"]["fax_detect"])) ? $body["pjsip"]["fax_detect"] : "no", "flags" => 0),
                array("id" => $trunkId, "keyword" => "forbidden_retry_interval", "data" => (isset($body["pjsip"]["forbidden_retry_interval"])) ? $body["pjsip"]["forbidden_retry_interval"] : "30", "flags" => 0),
                array("id" => $trunkId, "keyword" => "force_rport", "data" => (isset($body["pjsip"]["force_rport"])) ? $body["pjsip"]["force_rport"] : "yes", "flags" => 0),
                array("id" => $trunkId, "keyword" => "from_domain", "data" => (isset($body["pjsip"]["from_domain"])) ? $body["pjsip"]["from_domain"] : "", "flags" => 0),
                array("id" => $trunkId, "keyword" => "from_user", "data" => (isset($body["pjsip"]["from_user"])) ? $body["pjsip"]["from_user"] : "", "flags" => 0),
                array("id" => $trunkId, "keyword" => "hcid", "data" => (isset($body["pjsip"]["hcid"])) ? $body["pjsip"]["hcid"] : "on", "flags" => 0),
                array("id" => $trunkId, "keyword" => "identify_by", "data" => (isset($body["pjsip"]["identify_by"])) ? $body["pjsip"]["identify_by"] : "default", "flags" => 0),
                array("id" => $trunkId, "keyword" => "inband_progress", "data" => (isset($body["pjsip"]["inband_progress"])) ? $body["pjsip"]["inband_progress"] : "no", "flags" => 0),
                array("id" => $trunkId, "keyword" => "language", "data" => (isset($body["pjsip"]["language"])) ? $body["pjsip"]["language"] : "", "flags" => 0),
                array("id" => $trunkId, "keyword" => "match", "data" => (isset($body["pjsip"]["match"])) ? $body["pjsip"]["match"] : "", "flags" => 0),
                array("id" => $trunkId, "keyword" => "max_retries", "data" => (isset($body["pjsip"]["max_retries"])) ? $body["pjsip"]["max_retries"] : "10000", "flags" => 0),
                array("id" => $trunkId, "keyword" => "maxchans", "data" => (isset($body["pjsip"]["maxchans"])) ? $body["pjsip"]["maxchans"] : "", "flags" => 0),
                array("id" => $trunkId, "keyword" => "media_address", "data" => (isset($body["pjsip"]["media_address"])) ? $body["pjsip"]["media_address"] : "", "flags" => 0),
                array("id" => $trunkId, "keyword" => "media_encryption", "data" => (isset($body["pjsip"]["media_encryption"])) ? $body["pjsip"]["media_encryption"] : "no", "flags" => 0),
                array("id" => $trunkId, "keyword" => "message_context", "data" => (isset($body["pjsip"]["message_context"])) ? $body["pjsip"]["message_context"] : "", "flags" => 0),
                array("id" => $trunkId, "keyword" => "npanxx", "data" => (isset($body["pjsip"]["npanxx"])) ? $body["pjsip"]["npanxx"] : "", "flags" => 0),
                array("id" => $trunkId, "keyword" => "outbound_proxy", "data" => (isset($body["pjsip"]["outbound_proxy"])) ? $body["pjsip"]["outbound_proxy"] : "", "flags" => 0),
                array("id" => $trunkId, "keyword" => "peerdetails", "data" => (isset($body["pjsip"]["peerdetails"])) ? $body["pjsip"]["peerdetails"] : "", "flags" => 0),
                array("id" => $trunkId, "keyword" => "qualify_frequency", "data" => (isset($body["pjsip"]["qualify_frequency"])) ? $body["pjsip"]["qualify_frequency"] : "60", "flags" => 0),
                array("id" => $trunkId, "keyword" => "register", "data" => (isset($body["pjsip"]["register"])) ? $body["pjsip"]["register"] : "", "flags" => 0),
                array("id" => $trunkId, "keyword" => "registration", "data" => (isset($body["pjsip"]["registration"])) ? $body["pjsip"]["registration"] : "send", "flags" => 0),
                array("id" => $trunkId, "keyword" => "retry_interval", "data" => (isset($body["pjsip"]["retry_interval"])) ? $body["pjsip"]["retry_interval"] : "60", "flags" => 0),
                array("id" => $trunkId, "keyword" => "rewrite_contact", "data" => (isset($body["pjsip"]["rewrite_contact"])) ? $body["pjsip"]["rewrite_contact"] : "no", "flags" => 0),
                array("id" => $trunkId, "keyword" => "rtp_symmetric", "data" => (isset($body["pjsip"]["rtp_symmetric"])) ? $body["pjsip"]["rtp_symmetric"] : "yes", "flags" => 0),
                array("id" => $trunkId, "keyword" => "secret", "data" => (isset($body["pjsip"]["secret"])) ? $body["pjsip"]["secret"] : "", "flags" => 0),
                array("id" => $trunkId, "keyword" => "send_connected_line", "data" => (isset($body["pjsip"]["send_connected_line"])) ? $body["pjsip"]["send_connected_line"] : "false", "flags" => 0),
                array("id" => $trunkId, "keyword" => "sendrpid", "data" => (isset($body["pjsip"]["sendrpid"])) ? $body["pjsip"]["sendrpid"] : "no", "flags" => 0),
                array("id" => $trunkId, "keyword" => "server_uri", "data" => (isset($body["pjsip"]["server_uri"])) ? $body["pjsip"]["server_uri"] : "", "flags" => 0),
                array("id" => $trunkId, "keyword" => "sip_server", "data" => (isset($body["pjsip"]["sip_server"])) ? $body["pjsip"]["sip_server"] : "", "flags" => 0),
                array("id" => $trunkId, "keyword" => "sip_server_port", "data" => (isset($body["pjsip"]["sip_server_port"])) ? $body["pjsip"]["sip_server_port"] : "", "flags" => 0),
                array("id" => $trunkId, "keyword" => "support_path", "data" => (isset($body["pjsip"]["support_path"])) ? $body["pjsip"]["support_path"] : "yes", "flags" => 0),
                array("id" => $trunkId, "keyword" => "sv_channelid", "data" => (isset($body["pjsip"]["sv_channelid"])) ? $body["pjsip"]["sv_channelid"] : "", "flags" => 0),
                array("id" => $trunkId, "keyword" => "sv_trunk_name", "data" => (isset($body["pjsip"]["sv_trunk_name"])) ? $body["pjsip"]["sv_trunk_name"] : "", "flags" => 0),
                array("id" => $trunkId, "keyword" => "sv_usercontext", "data" => (isset($body["pjsip"]["sv_usercontext"])) ? $body["pjsip"]["sv_usercontext"] : "", "flags" => 0),
                array("id" => $trunkId, "keyword" => "t38_udptl", "data" => (isset($body["pjsip"]["t38_udptl"])) ? $body["pjsip"]["t38_udptl"] : "no", "flags" => 0),
                array("id" => $trunkId, "keyword" => "t38_udptl_ec", "data" => (isset($body["pjsip"]["t38_udptl_ec"])) ? $body["pjsip"]["t38_udptl_ec"] : "none", "flags" => 0),
                array("id" => $trunkId, "keyword" => "t38_udptl_maxdatagram", "data" => (isset($body["pjsip"]["t38_udptl_maxdatagram"])) ? $body["pjsip"]["t38_udptl_maxdatagram"] : "", "flags" => 0),
                array("id" => $trunkId, "keyword" => "t38_udptl_nat", "data" => (isset($body["pjsip"]["t38_udptl_nat"])) ? $body["pjsip"]["t38_udptl_nat"] : "no", "flags" => 0),
                array("id" => $trunkId, "keyword" => "transport", "data" => (isset($body["pjsip"]["transport"])) ? $body["pjsip"]["transport"] : "0.0.0.0-udp", "flags" => 0),
                array("id" => $trunkId, "keyword" => "trunk_name", "data" => (isset($body["pjsip"]["trunk_name"])) ? $body["pjsip"]["trunk_name"] : "", "flags" => 0),
                array("id" => $trunkId, "keyword" => "trust_id_outbound", "data" => (isset($body["pjsip"]["trust_id_outbound"])) ? $body["pjsip"]["trust_id_outbound"] : "no", "flags" => 0),
                array("id" => $trunkId, "keyword" => "trust_rpid", "data" => (isset($body["pjsip"]["trust_rpid"])) ? $body["pjsip"]["trust_rpid"] : "no", "flags" => 0),
                array("id" => $trunkId, "keyword" => "userconfig", "data" => (isset($body["pjsip"]["userconfig"])) ? $body["pjsip"]["userconfig"] : "", "flags" => 0),
                array("id" => $trunkId, "keyword" => "username", "data" => (isset($body["pjsip"]["username"])) ? $body["pjsip"]["username"] : "", "flags" => 0),
            );

            foreach ($pjsip as $setting) {
                $pjsipSQL = "insert into pjsip (id, keyword, data, flags) values (:id, :keyword, :data, :flags);";
                $stmt = $this->c->db->prepare($pjsipSQL);
                foreach ($setting as $key => &$val) {
                    $stmt->bindParam($key, $val);
                }
                $sipResult = $stmt->execute();
                $result['pjsip'] = $sipResult;
            }
        }

        return $response->withJson(array(
            'code' => 200,
            'data' => array('trunk' => $body['trunk']['name'], 'result' => $result)
        ));
    }
    public function updateSIPTrunk($request, $response, $args){
        global $chan_drivers;
        $body = $request->getParsedBody();

        return $response->withJson(array(
            'code' => 200,
            'data' => array('trunk' => $body['trunk']['name'], 'result' => $args)
        ));
    }
    //End Trunks

    //Inbound Routes
    public function getAllInboundRoutes($request, $response){
        $sql = "SELECT * FROM incoming;";
        $stmt = $this->c->db->query($sql);
        $inboundRoutes = $stmt->fetchAll();

        return $response->withJson(array(
            'code' => 200,
            'data' => $inboundRoutes
        ));
    }
    public function createInboundRoute($request, $response){
        global $destinationTypes;
        $body = $request->getParsedBody();

        if((!isset($body['did']) || $body['did'] == '') || $this->checkDuplicateInboundRouteDID($body['did'])){
            return $response->withJson(array(
                'code' => 501,
                'data' => 'DID not defined or Duplicate.'
            ));
        }
        elseif((!isset($body['description']) || $body['description'] == '') || $this->checkDuplicateInboundRouteName($body['description'])){
            return $response->withJson(array(
                'code' => 501,
                'data' => 'Description not defined or Duplicate.'
            ));
        }
        elseif(!isset($body['destination']) || $body['destination'] == ''){
            return $response->withJson(array(
                'code' => 501,
                'data' => 'Destination not defined.'
            ));
        }
        elseif(isset($body['destination_type']) && !array_key_exists($body['destination_type'], $destinationTypes)){
            $output = implode(', ', array_map(
                function ($v, $k) { return sprintf("%s", $k); },
                $destinationTypes,
                array_keys($destinationTypes)
            ));

            return $response->withJson(array(
                'code' => 501,
                'data' => 'Destination Type not defined. Possible Values (' . $output . ')'
            ));
        }
        elseif(isset($body['destination'])){
            $getDestinationId = $this->getDestinationIdByType($body['destination_type'], $body['destination']);
            if($getDestinationId){
                $body['destination'] = $getDestinationId;
            }
            else{
                return $response->withJson(array(
                    'code' => 501,
                    'data' => 'Invalid Destination Name. Try again with correct name.'
                ));
            }
        }

        $incoming = array(
            'cidnum'            => (isset($body['cidnum'])) ? $body['cidnum'] : '',
            'extension'         => $body['did'],
            'destination'       => $this->matchVariables($destinationTypes[$body['destination_type']], array('destination' => $body['destination'])),
            'privacyman'        => (isset($body['privacyman']) || $body['privacyman'] == 1) ? '1' : '',
            'alertinfo'         => (isset($body['alertinfo'])) ? $body['alertinfo'] : '',
            'ringing'           => (isset($body['ringing']) || $body['ringing'] == 1) ? 'CHECKED' : '',
            'fanswer'           => (isset($body['fanswer']) || $body['fanswer'] == 1) ? 'CHECKED' : '',
            'mohclass'          => (isset($body['mohclass']) || $body['mohclass'] != '') ? $body['mohclass'] : 'default',
            'description'       => $body['description'],
            'grppre'            => (isset($body['grppre'])) ? $body['grppre'] : '',
            'delay_answer'      => (isset($body['delay_answer'])) ? $body['delay_answer'] : 0,
            'pricid'            => (isset($body['pricid']) || $body['pricid'] == 1) ? 'CHECKED' : '',
            'pmmaxretries'      => (isset($body['pmmaxretries'])) ? $body['pmmaxretries'] : '',
            'pmminlength'       => (isset($body['pmminlength'])) ? $body['pmminlength'] : '',
            'reversal'          => (isset($body['reversal']) || $body['reversal'] == 1) ? 'CHECKED' : '',
            'rvolume'           => (isset($body['rvolume'])) ? $body['rvolume'] : '',
            'indication_zone'   => (isset($body['indication_zone'])) ? $body['indication_zone'] : 'default'
        );

        $incomingSQL = "insert into incoming (cidnum, extension, destination, privacyman, alertinfo, ringing, fanswer, mohclass, description, grppre, delay_answer, pricid, pmmaxretries, pmminlength, reversal, rvolume, indication_zone) values (:cidnum, :extension, :destination, :privacyman, :alertinfo, :ringing, :fanswer, :mohclass, :description, :grppre, :delay_answer, :pricid, :pmmaxretries, :pmminlength, :reversal, :rvolume, :indication_zone)";
        $stmt = $this->c->db->prepare($incomingSQL);
        foreach ($incoming as $key => &$val) {
            $stmt->bindParam($key, $val);
        }
        $incomingResult = $stmt->execute();
        $result['incoming'] = $incomingResult;

        $cidlookup = array(
            'cidlookup_id'  =>  0,
            'extension'     =>  $body['did'],
            'cidnum'        =>  (isset($body['cidnum'])) ? $body['cidnum'] : ''
        );

        $cidLookupSQL = "insert into cidlookup_incoming (cidlookup_id, extension, cidnum) values (:cidlookup_id, :extension, :cidnum)";
        $stmt = $this->c->db->prepare($cidLookupSQL);
        foreach ($cidlookup as $key => &$val) {
            $stmt->bindParam($key, $val);
        }
        $cidLookupResult = $stmt->execute();
        $result['cidLookup'] = $cidLookupResult;

        if(isset($body['fax_enabled']) && $body['fax_enabled'] == 1){
            $faxIncoming = array(
                'cidnum'        =>  (isset($body['cidnum'])) ? $body['cidnum'] : '',
                'extension'     =>  $body['did'],
                'detection'     =>  (isset($body['detection'])) ? $body['detection'] : 'sip',
                'detectionwait' =>  (isset($body['detectionwait'])) ? $body['detectionwait'] : '3',
                'destination'   =>  $this->matchVariables($destinationTypes[$body['destination_type']], array('destination' => $body['destination'])),
                'legacy_email'  =>  null,
                'ring'          =>  (isset($body['ring'])) ? $body['ring'] : 0
            );

            $faxIncomingSQL = "insert into fax_incoming (cidnum, extension, detection, detectionwait, destination, legacy_email, ring) values (:cidnum, :extension, :detection, :detectionwait, :destination, :legacy_email, :ring)";
            $stmt = $this->c->db->prepare($faxIncomingSQL);
            foreach ($faxIncoming as $key => &$val) {
                $stmt->bindParam($key, $val);
            }
            $faxIncomingResult = $stmt->execute();
            $result['faxIncoming'] = $faxIncomingResult;
        }

        if(isset($body['superfecta_enabled']) && $body['superfecta_enabled'] == 1){
            $superfecta = array(
                'extension'  =>  $body['did'],
                'cidnum'     =>  (isset($body['cidnum'])) ? $body['cidnum'] : '',
                'scheme'     =>  (isset($body['scheme'])) ? $body['scheme'] : 'base_Default'
            );

            $superfectaSQL = "insert into superfecta_to_incoming (extension, cidnum, scheme) values (:extension, :cidnum, :scheme)";
            $stmt = $this->c->db->prepare($superfectaSQL);
            foreach ($superfecta as $key => &$val) {
                $stmt->bindParam($key, $val);
            }
            $superfectaResult = $stmt->execute();
            $result['superfecta'] = $superfectaResult;
        }

        if($body['callrecording_enabled'] == 1){
            $recordingValues = array('force', 'dontcare', 'yes', 'no', 'never');
            $callRecording = array(
                'extension'     =>  $body['did'],
                'cidnum'        =>  (isset($body['cidnum'])) ? $body['cidnum'] : '',
                'callrecording' =>  (isset($body['callrecording']) && !in_array($body['callrecording'], $recordingValues)) ? $body['callrecording'] : 'dontcare',
                'display'       =>  'did'
            );

            $callRecordingSQL = "insert into callrecording_module (extension, cidnum, callrecording, display) values (:extension, :cidnum, :callrecording, :display);";
            $stmt = $this->c->db->prepare($callRecordingSQL);
            foreach ($callRecording as $key => &$val) {
                $stmt->bindParam($key, $val);
            }
            $callRecordingResult = $stmt->execute();
            $result['callRecording'] = $callRecordingResult;
        }

        return $response->withJson(array(
            'code' => 200,
            'data' => $result
        ));
    }
    //End Inbound Routes

    //Outbound Routes
    public function getAllOutboundRoutes($request, $response){
        $sql = "SELECT * FROM outbound_routes;";
        $stmt = $this->c->db->query($sql);
        $outboundRoutes = $stmt->fetchAll();

        return $response->withJson(array(
            'code' => 200,
            'data' => $outboundRoutes
        ));
    }
    public function createOutboundRoute($request, $response){
        global $destinationTypes, $timezones;
        $body = $request->getParsedBody();

        if((!isset($body['route']['name']) || $body['route']['name'] == '') || $this->checkDuplicateOutboundRouteName($body['route']['name'])){
            return $response->withJson(array(
                'code' => 501,
                'data' => 'Name not defined or Duplicate.'
            ));
        }
        elseif((!isset($body['route']['emergency_route']) || $body['route']['emergency_route'] != '') && (!isset($body['route']['intracompany_route'])  || $body['route']['intracompany_route'] != '')){
            return $response->withJson(array(
                'code' => 501,
                'data' => 'Emergency and Intra Company cannot be selected at the same time.'
            ));
        }
        elseif(isset($body['route']['timezone']) && !in_array($body['route']['timezone'], $timezones)){
            return $response->withJson(array(
                'code' => 501,
                'data' => "Timezone not correct. Possible Values ('" . implode("', '" , $timezones) . "')"
            ));
        }
        elseif(!isset($body['dialpatterns']) || count($body['dialpatterns']) <= 0 ){
            return $response->withJson(array(
                'code' => 501,
                'data' => "Dial Pattern not defined."
            ));
        }
        elseif(isset($body['route']['failed_destination_type']) && !array_key_exists($body['route']['failed_destination_type'], $destinationTypes)){
            $output = implode(', ', array_map(
                function ($v, $k) { return sprintf("%s", $k); },
                $destinationTypes,
                array_keys($destinationTypes)
            ));

            return $response->withJson(array(
                'code' => 501,
                'data' => 'Destination Type is Invalid. Possible Values (' . $output . ')'
            ));
        }
        elseif(isset($body['route']['failed_destination'])){
            $getDestinationId = $this->getDestinationIdByType($body['route']['failed_destination_type'], $body['route']['failed_destination']);
            if($getDestinationId){
                $body['route']['failed_destination'] = $getDestinationId;
            }
            else{
                return $response->withJson(array(
                    'code' => 501,
                    'data' => 'Invalid Destination Name. Try again with correct name.'
                ));
            }
        }
        elseif(!isset($body['trunks']) || count($body['trunks']) <= 0 ){
            return $response->withJson(array(
                'code' => 501,
                'data' => "Outbound Trunks not defined."
            ));
        }

        $outboundRoutes = array(
            "name"                  =>  $body['route']['name'],
            "outcid"                =>  (isset($body['route']['outcid'])) ? $body['route']['outcid'] : "",
            "outcid_mode"           =>  (isset($body['route']['outcid']) && $body['route']['outcid'] == 1) ? "override_extension" : "",
            "password"              =>  (isset($body['route']['password'])) ? $body['route']['password'] : "",
            "emergency_route"       =>  (isset($body['route']['emergency_route']) && $body['route']['emergency_route'] == 1) ? "YES" : "",
            "intracompany_route"    =>  (isset($body['route']['intracompany_route']) && $body['route']['intracompany_route'] == 1) ? "YES" : "",
            "mohclass"              =>  (isset($body['route']['mohclass'])) ? $body['route']['mohclass'] : "none",
            "time_group_id"         =>  (isset($body['route']['time_group_id'])) ? $this->getDestinationIdByType('time_group', $body['route']['time_group_id']) : "",
            "dest"                  =>  $this->matchVariables($destinationTypes[$body['route']['failed_destination_type']], array('destination' => $body['route']['failed_destination'])),
            "time_mode"             =>  "",
            "calendar_id"           =>  "null",
            "calendar_group_id"     =>  "null",
            "timezone"              =>  (isset($body['route']['timezone'])) ? $body['route']['timezone'] : 'default'
        );

        $outboundRoutesSQL = "insert into outbound_routes (name, outcid, outcid_mode, password, emergency_route, intracompany_route, mohclass, time_group_id, dest, time_mode, calendar_id, calendar_group_id, timezone) values (:name, :outcid, :outcid_mode, :password, :emergency_route, :intracompany_route, :mohclass, :time_group_id, :dest, :time_mode, :calendar_id, :calendar_group_id, :timezone)";
        $stmt = $this->c->db->prepare($outboundRoutesSQL);
        foreach ($outboundRoutes as $key => &$val) {
            $stmt->bindParam($key, $val);
        }
        $outboundRoutesResult = $stmt->execute();
        $outboundRouteId = $this->c->db->lastInsertId();

        $result['outboundRoute'] = $outboundRoutesResult;

        if (isset($body['dialpatterns']) && count($body['dialpatterns']) > 0) {
            foreach ($body['dialpatterns'] as $dialPattern) {
                $duplicateDialPattern = $this->checkDuplicateDialPattern($dialPattern, $outboundRouteId);

                if(!$duplicateDialPattern){
                    $pattern = array(
                        'route_id'              => $outboundRouteId,
                        'match_pattern_prefix'  => (isset($dialPattern['match_pattern_prefix'])) ? $dialPattern['match_pattern_prefix'] : "",
                        'match_pattern_pass'    => (isset($dialPattern['match_pattern_pass'])) ? $dialPattern['match_pattern_pass'] : "",
                        'match_cid'             => (isset($dialPattern['match_cid'])) ? $dialPattern['match_cid'] : "",
                        'prepend_digits'        => (isset($dialPattern['prepend_digits'])) ? $dialPattern['prepend_digits'] : 0,
                    );

                    $dialPatternSQL = "insert into outbound_route_patterns (route_id, match_pattern_prefix, match_pattern_pass, match_cid, prepend_digits) values (:route_id, :match_pattern_prefix, :match_pattern_pass, :match_cid, :prepend_digits)";
                    $stmt = $this->c->db->prepare($dialPatternSQL);
                    foreach ($pattern as $key => &$val) {
                        $stmt->bindParam($key, $val);
                    }
                    $dialPatternResult = $stmt->execute();
                    $result['dialplan_patterns'][] = $dialPatternResult;
                    $pattern = array();
                }
            }
        }

        if (isset($body['trunks']) && count($body['trunks']) > 0) {
            foreach ($body['trunks'] as $trunk) {
                $trunkId = $this->getDestinationIdByType('trunks', $trunk['trunk']);
                if($trunkId){
                    $trunks[] = array(
                        'route_id'  => $outboundRouteId,
                        'trunk_id'  => $trunkId,
                        'seq'       => $trunk['seq']
                    );
                }
            }

            if(isset($trunks) && count($trunks) > 0){
                foreach ($trunks as $setting) {
                    $trunksSQL = "insert into outbound_route_trunks (route_id, trunk_id, seq) values (:route_id, :trunk_id, :seq)";
                    $stmt = $this->c->db->prepare($trunksSQL);
                    foreach ($setting as $key => &$val) {
                        $stmt->bindParam($key, $val);
                    }
                    $dialPatternResult = $stmt->execute();
                    $result['trunks'] = $dialPatternResult;
                }
            }
        }

        $outboundRouteSequence = array(
            'route_id'  => $outboundRouteId,
            'seq'       => 0
        );
        $outboundRouteSequenceSQL = "insert into outbound_route_sequence (route_id, seq) values (:route_id, :seq)";
        $stmt = $this->c->db->prepare($outboundRouteSequenceSQL);
        foreach ($outboundRouteSequence as $key => &$val) {
            $stmt->bindParam($key, $val);
        }
        $outboundRouteSequenceResult = $stmt->execute();
        $result['outboundRouteSequence'] = $outboundRouteSequenceResult;

        if($body['callrecording_enabled'] == 1){
            $recordingValues = array('force', 'dontcare', 'yes', 'no', 'never');
            $callRecording = array(
                'extension'     =>  $outboundRouteId,
                'cidnum'        =>  (isset($body['cidnum'])) ? $body['cidnum'] : '',
                'callrecording' =>  (isset($body['callrecording']) && !in_array($body['callrecording'], $recordingValues)) ? $body['callrecording'] : 'dontcare',
                'display'       =>  'routing'
            );

            $callRecordingSQL = "insert into callrecording_module (extension, cidnum, callrecording, display) values (:extension, :cidnum, :callrecording, :display);";
            $stmt = $this->c->db->prepare($callRecordingSQL);
            foreach ($callRecording as $key => &$val) {
                $stmt->bindParam($key, $val);
            }
            $callRecordingResult = $stmt->execute();
            $result['callRecording'] = $callRecordingResult;
        }

        return $response->withJson(array(
            'code' => 200,
            'data' => $result
        ));
    }
    //End Outbound Routes


    public function checkSQLite($request, $response){
        $body = $request->getParsedBody();
        $sql = "SELECT * FROM astdb where key like '%/100%';";
        $stmt = $this->c->sqlite->query($sql);
        $inboundRoutes = $stmt->fetchAll();

        return $response->withJson(array(
            'code' => 200,
            'data' => $inboundRoutes
        ));
    }

}