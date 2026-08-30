<?php

namespace Database\Seeders;

use App\Models\RiskAssessment;
use Illuminate\Database\Seeder;

class RiskSeeder extends Seeder
{
    public function run()
    {
        $risks = [
            ['asset_id' => 1, 'threat' => 'Unauthorized Access', 'vulnerability' => 'Weak Passwords', 'likelihood' => 4, 'impact' => 5, 'status' => 'identified'],
            ['asset_id' => 2, 'threat' => 'Network Outage', 'vulnerability' => 'Single Point of Failure', 'likelihood' => 3, 'impact' => 5, 'status' => 'under_review'],
            ['asset_id' => 3, 'threat' => 'Data Breach', 'vulnerability' => 'Lost/Stolen Device', 'likelihood' => 4, 'impact' => 5, 'status' => 'identified'],
            ['asset_id' => 4, 'threat' => 'Service Disruption', 'vulnerability' => 'Cloud Provider Outage', 'likelihood' => 3, 'impact' => 4, 'status' => 'in_treatment'],
            ['asset_id' => 5, 'threat' => 'Insider Threat', 'vulnerability' => 'Lack of Access Controls', 'likelihood' => 3, 'impact' => 5, 'status' => 'identified'],
            ['asset_id' => 6, 'threat' => 'Code Injection', 'vulnerability' => 'Insecure Code Practices', 'likelihood' => 4, 'impact' => 4, 'status' => 'under_review'],
            ['asset_id' => 7, 'threat' => 'Physical Break-in', 'vulnerability' => 'Weak Physical Security', 'likelihood' => 2, 'impact' => 4, 'status' => 'identified'],
            ['asset_id' => 8, 'threat' => 'Data Loss', 'vulnerability' => 'Backup Failure', 'likelihood' => 2, 'impact' => 5, 'status' => 'in_treatment'],
            ['asset_id' => 9, 'threat' => 'Phishing Attack', 'vulnerability' => 'Lack of User Awareness', 'likelihood' => 5, 'impact' => 4, 'status' => 'identified'],
            ['asset_id' => 10, 'threat' => 'Fraud', 'vulnerability' => 'Weak Financial Controls', 'likelihood' => 2, 'impact' => 5, 'status' => 'identified'],
        ];

        foreach ($risks as $risk) {
            $risk['tenant_id'] = 1;
            $risk['risk_score'] = $risk['likelihood'] * $risk['impact'];
            $risk['risk_level'] = self::getRiskLevel($risk['risk_score']);
            RiskAssessment::create($risk);
        }
    }

    private static function getRiskLevel($score)
    {
        if ($score >= 17)
            return 'critical';
        if ($score >= 10)
            return 'high';
        if ($score >= 5)
            return 'medium';
        return 'low';
    }
}