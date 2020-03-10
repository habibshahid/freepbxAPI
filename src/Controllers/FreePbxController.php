<?php

namespace App\Controllers;

class FreePbxController extends Controller {
    public function __construct($container)
    {
        parent::__construct($container);
        global $chan_drivers;
        $chan_drivers = array(
            'sip'   => 'chan_sip',
            'pjsip' => 'chan_pjsip',
            'SIP'   => 'chan_sip',
            'PJSIP' => 'chan_pjsip',
        );
    }

    public function getAllSIPExtensions($request, $response) {
	    $sql = "SELECT * FROM sip;";
	    $stmt = $this->c->db->query($sql);
	    $sipExtensions = $stmt->fetchAll();

	    return $response->withJson(array(
	        'code' => 200,
            'data' => $sipExtensions
        ));
	}

	private function checkDuplicateExtension($extension){
        $sql = "SELECT * FROM sip where id = '". $extension ."';";
        $stmt = $this->c->db->query($sql);
        $sipExtensions = $stmt->fetchAll();

        if(count($sipExtensions) > 0){
            return true;
        }
        else{
            return false;
        }
    }

    private function checkDuplicateDevice($extension){
        $sql = "SELECT * FROM devices where id = '". $extension ."';";
        $stmt = $this->c->db->query($sql);
        $sipExtensions = $stmt->fetchAll();

        if(count($sipExtensions) > 0){
            return true;
        }
        else{
            return false;
        }
    }

    private function checkDuplicateFollowMe($extension){
        $sql = "SELECT * FROM findmefollow where grpnum = '". $extension ."';";
        $stmt = $this->c->db->query($sql);
        $sipExtensions = $stmt->fetchAll();

        if(count($sipExtensions) > 0){
            return true;
        }
        else{
            return false;
        }
    }

    private function checkDuplicateUCP($extension){
        $sql = "SELECT * FROM userman_users where username = '". $extension ."';";
        $stmt = $this->c->db->query($sql);
        $sipExtensions = $stmt->fetchAll();

        if(count($sipExtensions) > 0){
            return true;
        }
        else{
            return false;
        }
    }

    private function checkDuplicateUser($extension){
        $sql = "SELECT * FROM users where extension = '". $extension ."';";
        $stmt = $this->c->db->query($sql);
        $sipExtensions = $stmt->fetchAll();

        if(count($sipExtensions) > 0){
            return true;
        }
        else{
            return false;
        }
    }

	public function createSIPExtension($request, $response){
        global $chan_drivers;
        $body = $request->getParsedBody();

	    if(!$body['extension'] && $body['extension'] == ''){
            return $response->withJson(array(
                'code' => 501,
                'data' => 'Extension not defined.'
            ));
        }
	    elseif(!isset($body['displayname']) || $body['displayname'] == ''){
            return $response->withJson(array(
                'code' => 501,
                'data' => 'Display Name not defined.'
            ));
        }
        elseif(!isset($body['devicetype']) || ($body['devicetype'] == '' || !array_key_exists($body['devicetype'], $chan_drivers))){
            return $response->withJson(array(
                'code' => 501,
                'data' => 'Device Type not defined. Possible Types (SIP/PJSIP)'
            ));
        }
        elseif(!isset($body['secret']) || $body['secret'] == ''){
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

        if($duplicate['extension'] || $duplicate['device']){
            return $response->withJson(array(
                'code' => 403,
                'data' => 'Extension already Exists. Cannot create extension / device ' . $body['extension']
            ));
        }

        if($chan_drivers[$body['devicetype']] == 'chan_sip'){
            $tech = 'sip';
            $dial = 'SIP';
        }
        else{
            $tech = 'pjsip';
            $dial = 'PJSIP';
        }

	    $devices = array(
	        'id'            =>  $body['extension'],
            'tech'          =>  $tech,
            'dial'          =>  $dial . '/' . $body['extension'],
            'devicetype'    =>  'fixed',
            'user'          =>  $body['extension'],
            'description'   =>  $body['displayname'],
            'emergency_cid' =>  (isset($body['emergencycid'])) ? $body['emergencycid'] : '',
            'hint_override' =>  (isset($body['hint_override'])) ? $body['hint_override'] : null
        );
        $sql = "insert into devices (id, tech, dial, devicetype, user, description, emergency_cid, hint_override) values (:id, :tech, :dial, :devicetype, :user, :description, :emergency_cid, :hint_override)";
        $stmt = $this->c->db->prepare($sql);
        foreach ($devices as $key => &$val) {
            $stmt->bindParam($key, $val);
        }
        $devicesResult = $stmt->execute();
        $result['devices'] = $devicesResult;

	    $findmefollow = array(
	        'grpnum'            =>  $body['extension'],
            'strategy'          =>  (isset($body['followmestrategy'])) ? $body['followmestrategy'] : 'ringallv2-prim',
            'grptime'           =>  (isset($body['grptime'])) ? $body['grptime'] : '20',
            'grppre'            =>  (isset($body['grppre'])) ? $body['grppre'] : '',
            'grplist'           =>  $body['extension'],
            'annmsg_id'         =>  (isset($body['annmsg_id'])) ? $body['annmsg_id'] : null,
            'postdest'          =>  'ext-local,' . $body['extension'] . ',dest',
            'dring'             =>  (isset($body['dring'])) ? $body['dring'] : '',
            'rvolume'           =>  (isset($body['rvolume'])) ? $body['rvolume'] : '',
            'remotealert_id'    =>  (isset($body['remotealert_id'])) ? $body['remotealert_id'] : null,
            'needsconf'         =>  (isset($body['needsconf'])) ? $body['needsconf'] : '',
            'toolate_id'        =>  (isset($body['toolate_id'])) ? $body['toolate_id'] : null,
            'pre_ring'          =>  (isset($body['pre_ring'])) ? $body['pre_ring'] : 7,
            'ringing'           =>  (isset($body['ringing'])) ? $body['ringing'] : 'Ring',
            'calendar_enable'   =>  (isset($body['calendar_enable'])) ? $body['calendar_enable'] : '',
            'calendar_id'       =>  (isset($body['calendar_id'])) ? $body['calendar_id'] : '',
            'calendar_group_id' =>  (isset($body['calendar_group_id'])) ? $body['calendar_group_id'] : '',
            'calendar_match'    =>  (isset($body['calendar_match'])) ? $body['calendar_match'] : 'yes',
        );

	    if(!$duplicate['followme']){
            $sql = "insert into findmefollow (grpnum, strategy, grptime, grppre, grplist, annmsg_id, postdest, dring, rvolume, remotealert_id, needsconf, toolate_id, pre_ring, ringing, calendar_enable, calendar_id, calendar_group_id, calendar_match) values (:grpnum, :strategy, :grptime, :grppre, :grplist, :annmsg_id, :postdest, :dring, :rvolume, :remotealert_id, :needsconf, :toolate_id, :pre_ring, :ringing, :calendar_enable, :calendar_id, :calendar_group_id, :calendar_match)";
            $stmt = $this->c->db->prepare($sql);
            foreach ($findmefollow as $key => &$val) {
                $stmt->bindParam($key, $val);
            }
            $findmefollowResult = $stmt->execute();
            $result['findmefollow'] = $findmefollowResult;
        }
	    else{
            $result['findmefollow'] = 'Cannot create Follow Me for Extension ' . $body['extension'] . '. Configuration already exists.';
        }

	    if(isset($body['incomingdid']) && $body['incomingdid'] != ''){
            $duplicate['incoming'] = $this->checkDuplicateFollowMe($body['incomingdid']);
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

            if(!$duplicate['incomingdid']){
                $sql = "insert into incoming (cidnum, extension, destination, privacyman, alertinfo, ringing, fanswer, mohclass, description, grppre, delay_answer, pricid, pmmaxretries, pmminlength, reversal, rvolume, indication_zone) values (:cidnum, :extension, :destination, :privacyman, :alertinfo, :ringing, :fanswer, :mohclass, :description, :grppre, :delay_answer, :pricid, :pmmaxretries, :pmminlength, :reversal, :rvolume, :indication_zone)";
                $stmt = $this->c->db->prepare($sql);
                foreach ($incoming as $key => &$val) {
                    $stmt->bindParam($key, $val);
                }
                $incomingResult = $stmt->execute();
                $result['incoming'] = $incomingResult;
            }
            else{
                $result['incoming'] = 'Cannot create Incoming DID Route for Extension ' . $body['extension'] . '. Configuration already exists.';
            }
        }

        $settings  = array(
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
                "data" => $body['displayname'] . ' <' . $body['extension'] .'>',
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
                "data" =>  (isset($body['deny'])) ? $body['deny'] : "0.0.0.0/0.0.0.0",
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
                "keyword" => "secret_origional" ,
                "data" =>  $body['secret'],
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

	    foreach($settings as $setting){
            $sql = "insert into sip (id, keyword, data, flags) values (:id, :keyword, :data, :flags)";
            $stmt = $this->c->db->prepare($sql);
            foreach ($setting as $key => &$val) {
                $stmt->bindParam($key, $val);
            }
            $sipResult = $stmt->execute();
            $result['sip'] = $sipResult;
        }

        $userman_users = array(
            'auth'                  =>  (isset($body['auth'])) ? $body['auth'] : 1,
            'authid'                =>  (isset($body['authid'])) ? $body['authid'] : null,
            'username'              =>  $body['extension'],
            'description'           =>  'Autogenerated user on new device creation',
            'password'              =>  '$2a$08$QR/ZjchVw4Mgsyoz4okE9ulZxAWhpeKsvQwuWLoH7e70GIkZQ4jUC', //To Do check freepbx password encryption
            'default_extension'     =>  $body['extension'],
            'primary_group'         =>  (isset($body['primary_group'])) ? $body['primary_group'] : null,
            'permissions'           =>  (isset($body['permissions'])) ? $body['permissions'] : null,
            'fname'                 =>  (isset($body['fname'])) ? $body['fname'] : null,
            'lname'                 =>  (isset($body['lname'])) ? $body['lname'] : null,
            'displayname'           =>  $body['displayname'],
            'title'                 =>  (isset($body['lname'])) ? $body['lname'] : null,
            'company'               =>  (isset($body['company'])) ? $body['company'] : null,
            'department'            =>  (isset($body['department'])) ? $body['department'] : null,
            'language'              =>  (isset($body['language'])) ? $body['language'] : null,
            'timezone'              =>  (isset($body['timezone'])) ? $body['timezone'] : null,
            'dateformat'            =>  (isset($body['dateformat'])) ? $body['dateformat'] : null,
            'timeformat'            =>  (isset($body['timeformat'])) ? $body['timeformat'] : null,
            'datetimeformat'        =>  (isset($body['datetimeformat'])) ? $body['datetimeformat'] : null,
            'email'                 =>  (isset($body['email'])) ? $body['email'] : null,
            'cell'                  =>  (isset($body['cell'])) ? $body['cell'] : null,
            'work'                  =>  (isset($body['work'])) ? $body['work'] : null,
            'home'                  =>  (isset($body['home'])) ? $body['home'] : null,
            'fax'                   =>  (isset($body['fax'])) ? $body['fax'] : null,
        );

        if(!$duplicate['ucp']){
            $sql = "insert into userman_users (auth, authid, username, description, password, default_extension, primary_group, permissions, fname, lname, displayname, title, company, department, language, timezone, dateformat, timeformat, datetimeformat, email, cell, work, home, fax) values (:auth, :authid, :username, :description, :password, :default_extension, :primary_group, :permissions, :fname, :lname, :displayname, :title, :company, :department, :language, :timezone, :dateformat, :timeformat, :datetimeformat, :email, :cell, :work, :home, :fax)";
            $stmt = $this->c->db->prepare($sql);
            foreach ($userman_users as $key => &$val) {
                $stmt->bindParam($key, $val);
            }
            $userman_usersResult = $stmt->execute();
            $result['userman_users'] = $userman_usersResult;
        }
        else{
            $result['userman_users'] = 'Cannot create UCP User for Extension ' . $body['extension'] . '. Configuration already exists.';
        }

        $users = array(
            'extension'         =>  $body['extension'],
            'password'          =>  '',
            'name'              =>  $body['displayname'],
            'voicemail'         =>  (isset($body['voicemail'])) ? $body['voicemail'] : 'novm',
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

        if(!$duplicate['user']){
            $sql = "insert into users (extension, password, name, voicemail, ringtimer, noanswer, recording, outboundcid, sipname, noanswer_cid, busy_cid, chanunavail_cid, noanswer_dest, busy_dest, chanunavail_dest, mohclass) values (:extension, :password, :name, :voicemail, :ringtimer, :noanswer, :recording, :outboundcid, :sipname, :noanswer_cid, :busy_cid, :chanunavail_cid, :noanswer_dest, :busy_dest, :chanunavail_dest, :mohclass)";
            $stmt = $this->c->db->prepare($sql);
            foreach ($users as $key => &$val) {
                $stmt->bindParam($key, $val);
            }
            $usersResult = $stmt->execute();
            $result['users'] = $usersResult;
        }
        else{
            $result['users'] = 'Cannot create User for Extension ' . $body['extension'] . '. Configuration already exists.';
        }

        return $response->withJson(array(
            'code' => 200,
            'data' => array('extension' => $body['extension'], 'result' => $result)
        ));
    }
}