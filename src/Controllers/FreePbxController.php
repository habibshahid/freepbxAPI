<?php

namespace App\Controllers;

class FreePbxController extends Controller
{
    public function __construct($container)
    {
        parent::__construct($container);
        global $chan_drivers;
        $chan_drivers = array(
            'sip' => 'chan_sip',
            'pjsip' => 'chan_pjsip',
            'SIP' => 'chan_sip',
            'PJSIP' => 'chan_pjsip',
        );
    }

    public function getAllSIPExtensions($request, $response){
        $sql = "SELECT * FROM devices;";
        $stmt = $this->c->db->query($sql);
        $sipExtensions = $stmt->fetchAll();

        return $response->withJson(array(
            'code' => 200,
            'data' => $sipExtensions
        ));
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

    public function createSIPExtension($request, $response){
        global $chan_drivers;
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
            'id' => $body['extension'],
            'tech' => $tech,
            'dial' => $dial . '/' . $body['extension'],
            'devicetype' => 'fixed',
            'user' => $body['extension'],
            'description' => $body['displayname'],
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

        $findmefollow = array(
            'grpnum' => $body['extension'],
            'strategy' => (isset($body['followmestrategy'])) ? $body['followmestrategy'] : 'ringallv2-prim',
            'grptime' => (isset($body['grptime'])) ? $body['grptime'] : '20',
            'grppre' => (isset($body['grppre'])) ? $body['grppre'] : '',
            'grplist' => $body['extension'],
            'annmsg_id' => (isset($body['annmsg_id'])) ? $body['annmsg_id'] : null,
            'postdest' => 'ext-local,' . $body['extension'] . ',dest',
            'dring' => (isset($body['dring'])) ? $body['dring'] : '',
            'rvolume' => (isset($body['rvolume'])) ? $body['rvolume'] : '',
            'remotealert_id' => (isset($body['remotealert_id'])) ? $body['remotealert_id'] : null,
            'needsconf' => (isset($body['needsconf'])) ? $body['needsconf'] : '',
            'toolate_id' => (isset($body['toolate_id'])) ? $body['toolate_id'] : null,
            'pre_ring' => (isset($body['pre_ring'])) ? $body['pre_ring'] : 7,
            'ringing' => (isset($body['ringing'])) ? $body['ringing'] : 'Ring',
            'calendar_enable' => (isset($body['calendar_enable'])) ? $body['calendar_enable'] : '',
            'calendar_id' => (isset($body['calendar_id'])) ? $body['calendar_id'] : '',
            'calendar_group_id' => (isset($body['calendar_group_id'])) ? $body['calendar_group_id'] : '',
            'calendar_match' => (isset($body['calendar_match'])) ? $body['calendar_match'] : 'yes',
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

        if (isset($body['incomingdid']) && $body['incomingdid'] != '') {
            $duplicate['incoming'] = $this->checkDuplicateFollowMe($body['incomingdid']);
            $incoming = array(
                'cidnum' => (isset($body['cidnum'])) ? $body['cidnum'] : '',
                'extension' => $body['incomingdid'],
                'destination' => 'from-did,' . $body['extension'] . ',1',
                'privacyman' => 0,
                'alertinfo' => (isset($body['alertinfo'])) ? $body['alertinfo'] : '',
                'ringing' => (isset($body['ringing'])) ? $body['ringing'] : '',
                'fanswer' => (isset($body['fanswer'])) ? $body['fanswer'] : '',
                'mohclass' => (isset($body['mohclass'])) ? $body['mohclass'] : 'default',
                'description' => $body['incomingdid'],
                'grppre' => (isset($body['grppre'])) ? $body['grppre'] : '',
                'delay_answer' => (isset($body['delay_answer'])) ? $body['delay_answer'] : 0,
                'pricid' => (isset($body['pricid'])) ? $body['pricid'] : '',
                'pmmaxretries' => (isset($body['pmmaxretries'])) ? $body['pmmaxretries'] : '',
                'pmminlength' => (isset($body['pmminlength'])) ? $body['pmminlength'] : '',
                'reversal' => (isset($body['reversal'])) ? $body['reversal'] : '',
                'rvolume' => (isset($body['rvolume'])) ? $body['rvolume'] : '',
                'indication_zone' => (isset($body['indication_zone'])) ? $body['indication_zone'] : 'default'
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
        } elseif ($tech == 'pjsip') {
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
                'auth' => (isset($body['auth'])) ? $body['auth'] : 1,
                'authid' => (isset($body['authid'])) ? $body['authid'] : null,
                'username' => $body['extension'],
                'description' => 'Autogenerated user on new device creation',
                'password' => '$2a$08$QR/ZjchVw4Mgsyoz4okE9ulZxAWhpeKsvQwuWLoH7e70GIkZQ4jUC', //To Do check freepbx password encryption
                'default_extension' => $body['extension'],
                'primary_group' => (isset($body['primary_group'])) ? $body['primary_group'] : null,
                'permissions' => (isset($body['permissions'])) ? $body['permissions'] : null,
                'fname' => (isset($body['fname'])) ? $body['fname'] : null,
                'lname' => (isset($body['lname'])) ? $body['lname'] : null,
                'displayname' => $body['displayname'],
                'title' => (isset($body['lname'])) ? $body['lname'] : null,
                'company' => (isset($body['company'])) ? $body['company'] : null,
                'department' => (isset($body['department'])) ? $body['department'] : null,
                'language' => (isset($body['language'])) ? $body['language'] : null,
                'timezone' => (isset($body['timezone'])) ? $body['timezone'] : null,
                'dateformat' => (isset($body['dateformat'])) ? $body['dateformat'] : null,
                'timeformat' => (isset($body['timeformat'])) ? $body['timeformat'] : null,
                'datetimeformat' => (isset($body['datetimeformat'])) ? $body['datetimeformat'] : null,
                'email' => (isset($body['email'])) ? $body['email'] : null,
                'cell' => (isset($body['cell'])) ? $body['cell'] : null,
                'work' => (isset($body['work'])) ? $body['work'] : null,
                'home' => (isset($body['home'])) ? $body['home'] : null,
                'fax' => (isset($body['fax'])) ? $body['fax'] : null,
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
            'extension' => $body['extension'],
            'password' => '',
            'name' => $body['displayname'],
            'voicemail' => (isset($body['voicemail'])) ? $body['voicemail'] : 'novm',
            'ringtimer' => (isset($body['ringtimer'])) ? $body['ringtimer'] : 0,
            'noanswer' => (isset($body['noanswer'])) ? $body['noanswer'] : '',
            'recording' => (isset($body['recording'])) ? $body['recording'] : '',
            'outboundcid' => (isset($body['outboundcid'])) ? $body['outboundcid'] : '',
            'sipname' => (isset($body['sipname'])) ? $body['sipname'] : '',
            'noanswer_cid' => (isset($body['noanswer_cid'])) ? $body['noanswer_cid'] : '',
            'busy_cid' => (isset($body['busy_cid'])) ? $body['busy_cid'] : '',
            'chanunavail_cid' => (isset($body['chanunavail_cid'])) ? $body['chanunavail_cid'] : '',
            'noanswer_dest' => (isset($body['noanswer_dest'])) ? $body['noanswer_dest'] : '',
            'busy_dest' => (isset($body['busy_dest'])) ? $body['busy_dest'] : '',
            'chanunavail_dest' => (isset($body['chanunavail_dest'])) ? $body['chanunavail_dest'] : '',
            'mohclass' => (isset($body['mohclass'])) ? $body['mohclass'] : 'default',
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

    private function getTrunkId(){
        $sql = "SELECT trunkid FROM trunks order by trunkid desc limit 1;";
        $stmt = $this->c->db->query($sql);
        $trunkId = $stmt->fetch();

        return $trunkId['trunkid'];
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
            'trunkid' => $trunkId,
            'tech' => $body['trunk']['tech'],
            'name' => trim($body['trunk']['name']),
            'channelid' => (isset($body['peercontext']['name']) && $body['trunk']['tech'] == 'sip') ? $body['peercontext']['name'] : trim($body['trunk']['name']),
            'outcid' => (isset($body['trunk']['outcid'])) ? $body['trunk']['outcid'] : "",
            'keepcid' => (isset($body['trunk']['keepcid'])) ? $body['trunk']['keepcid'] : "off",
            'maxchans' => (isset($body['trunk']['maxchans'])) ? $body['trunk']['maxchans'] : "",
            'failscript' => (isset($body['trunk']['failscript'])) ? $body['trunk']['failscript'] : "",
            'dialoutprefix' => (isset($body['trunk']['dialoutprefix'])) ? $body['trunk']['dialoutprefix'] : "",
            'usercontext' => (isset($body['usercontext']['name']) && $body['trunk']['tech'] != 'pjsip') ? $body['usercontext']['name'] : "",
            'provider' => (isset($body['trunk']['provider'])) ? $body['trunk']['provider'] : "",
            'disabled' => (isset($body['trunk']['disabled'])) ? $body['trunk']['disabled'] : "off",
            'continue' => (isset($body['trunk']['continue'])) ? $body['trunk']['continue'] : "off",
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
                    'trunkid' => $trunkId,
                    'match_pattern_prefix' => (isset($dialPattern['match_pattern_prefix'])) ? $dialPattern['match_pattern_prefix'] : "",
                    'match_pattern_pass' => (isset($dialPattern['match_pattern_pass'])) ? $dialPattern['match_pattern_prefix'] : "",
                    'prepend_digits' => (isset($dialPattern['prepend_digits'])) ? $dialPattern['prepend_digits'] : "",
                    'seq' => (isset($dialPattern['seq'])) ? $dialPattern['seq'] : 0,
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
}