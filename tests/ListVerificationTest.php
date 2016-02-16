<?php namespace NeverBounce;

use NeverBounce\Object\ListVerification;

class ListVerificationTest extends TestCase {

    public function testSampledList()
    {
        $data = json_decode($this->json, true);
        $list = new ListVerification($data);

        $this->assertEquals('21958', $list->id);
        $this->assertEquals(ListVerification::VERIFICATION_STATUS_INDEXED, $list->status);
        $this->assertFalse($list->isCompleted());
        $this->assertFalse($list->isRunning());
        $this->assertTrue($list->isSampleCompleted());
        $this->assertFalse($list->isSampleRunning());
        $this->assertEquals(3, $list->getSampleStatus());
    }

    protected $json = '{
        "success": true,
        "id": "21958",
        "status": "2",
        "type": "0",
        "input_location": "2",
        "orig_name": "cleantest_256.csv",
        "stats": {
            "total": 256,
            "processed": 256,
            "valid": 0,
            "invalid": 0,
            "bad_syntax": 0,
            "catchall": 0,
            "disposable": 0,
            "unknown": 0,
            "duplicates": 0,
            "billable": 256,
            "job_time": 50
        },
        "created": "2016-02-08 15:31:18",
        "started": "2016-02-08 15:31:30",
        "finished": "2016-02-08 15:32:20",
        "file_details": "{\"error\":false,\"email_col_i\":0,\"tot_cols\":2,\"delimiter\":\",\",\"has_header\":false,\"size\":7902,\"tot_records\":257,\"tot_emails\":256}",
        "job_details": {
            "sample_status": 3,
            "sample_details": {
                "sample_size": 256,
                "sample_stats": {
                    "total": 256,
                    "processed": 256,
                    "valid": 199,
                    "invalid": 7,
                    "bad_syntax": 0,
                    "catchall": 32,
                    "disposable": 0,
                    "unknown": 18,
                    "duplicates": 0,
                    "billable": 256,
                    "percent_complete": 100,
                    "t": 256,
                    "p": 256,
                    "g": 199,
                    "b": 7,
                    "c": 32,
                    "d": 0,
                    "u": 18
                },
                "bounce_estimate": 0.02734375
            }
        },
        "execution_time": 0.071669101715088
    }';

}