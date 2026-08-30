<?php

namespace Database\Seeders;

use App\Models\Control;
use App\Models\Domain;
use Illuminate\Database\Seeder;

class ControlSeeder extends Seeder
{
    public function run(): void
    {
        // Main Clause Controls
        $this->seedMainClauseControls();
        
        // Annex A Controls
        $this->seedAnnexAControls();
    }

    private function seedMainClauseControls(): void
    {
        $mainClauseControls = [
            // Clause 4: Context of the Organization
            ['4.1', 'Understanding the organization and its context', 'The organization shall determine external and internal issues that are relevant to its purpose and that affect its ability to achieve the intended outcomes of its information security management system.', 'organizational', 'preventive'],
            ['4.2', 'Understanding the needs and expectations of interested parties', 'The organization shall determine interested parties relevant to the ISMS and their requirements.', 'organizational', 'preventive'],
            ['4.3', 'Determining the scope of the ISMS', 'The organization shall determine the boundaries and applicability of the ISMS to establish its scope.', 'organizational', 'preventive'],
            ['4.4', 'Information security management system', 'The organization shall establish, implement, maintain and continually improve an ISMS.', 'organizational', 'preventive'],
            
            // Clause 5: Leadership
            ['5.1', 'Leadership and commitment', 'Top management shall demonstrate leadership and commitment with respect to the ISMS.', 'organizational', 'preventive'],
            ['5.2', 'Information security policy', 'Top management shall establish an information security policy that is appropriate to the purpose of the organization.', 'organizational', 'preventive'],
            ['5.3', 'Organizational roles, responsibilities and authorities', 'Top management shall ensure that the responsibilities and authorities for roles relevant to information security are assigned and communicated.', 'organizational', 'preventive'],
            
            // Clause 6: Planning
            ['6.1', 'Actions to address risks and opportunities', 'The organization shall plan actions to address risks and opportunities relevant to the ISMS.', 'organizational', 'preventive'],
            ['6.2', 'Information security objectives and planning to achieve them', 'The organization shall establish information security objectives at relevant functions and levels.', 'organizational', 'preventive'],
            
            // Clause 7: Support
            ['7.1', 'Resources', 'The organization shall determine and provide the resources needed for the ISMS.', 'organizational', 'preventive'],
            ['7.2', 'Competence', 'The organization shall determine the necessary competence of persons doing work under its control.', 'organizational', 'preventive'],
            ['7.3', 'Awareness', 'Persons doing work under the organizations control shall be aware of the information security policy.', 'organizational', 'preventive'],
            ['7.4', 'Communication', 'The organization shall determine the need for internal and external communications relevant to the ISMS.', 'organizational', 'preventive'],
            ['7.5', 'Documented information', 'The organizations ISMS shall include documented information required by ISO 27001.', 'organizational', 'preventive'],
            
            // Clause 8: Operation
            ['8.1', 'Operational planning and control', 'The organization shall plan, implement and control the processes needed to meet ISMS requirements.', 'organizational', 'preventive'],
            ['8.2', 'Information security risk assessment', 'The organization shall perform information security risk assessments at planned intervals.', 'organizational', 'preventive'],
            ['8.3', 'Information security risk treatment', 'The organization shall implement the information security risk treatment plan.', 'organizational', 'preventive'],
            
            // Clause 9: Performance Evaluation
            ['9.1', 'Monitoring, measurement, analysis and evaluation', 'The organization shall evaluate the information security performance and the effectiveness of the ISMS.', 'organizational', 'detective'],
            ['9.2', 'Internal audit', 'The organization shall conduct internal audits at planned intervals to provide information on whether the ISMS conforms to requirements.', 'organizational', 'detective'],
            ['9.3', 'Management review', 'Top management shall review the organizations ISMS at planned intervals to ensure its continuing suitability, adequacy and effectiveness.', 'organizational', 'detective'],
            
            // Clause 10: Improvement
            ['10.1', 'Continual improvement', 'The organization shall continually improve the suitability, adequacy and effectiveness of the ISMS.', 'organizational', 'corrective'],
            ['10.2', 'Nonconformity and corrective action', 'When a nonconformity occurs, the organization shall react to the nonconformity and take action.', 'organizational', 'corrective'],
        ];

        $domain4 = Domain::where('code', '4')->first();
        $domain5 = Domain::where('code', '5')->first();
        $domain6 = Domain::where('code', '6')->first();
        $domain7 = Domain::where('code', '7')->first();
        $domain8 = Domain::where('code', '8')->first();
        $domain9 = Domain::where('code', '9')->first();
        $domain10 = Domain::where('code', '10')->first();

        $domainMap = [
            '4' => $domain4,
            '5' => $domain5,
            '6' => $domain6,
            '7' => $domain7,
            '8' => $domain8,
            '9' => $domain9,
            '10' => $domain10,
        ];

        foreach ($mainClauseControls as $control) {
            $domainCode = explode('.', $control[0])[0];
            Control::create([
                'domain_id' => $domainMap[$domainCode]->id,
                'control_id' => $control[0],
                'title' => $control[1],
                'description' => $control[2],
                'category' => $control[3],
                'control_type' => $control[4],
                'is_active' => true,
            ]);
        }
    }

    private function seedAnnexAControls(): void
    {
        $annexAControls = [
            // A.5 Organizational Controls
            ['A.5.1', 'Policies for information security', 'Information security policy and topic-specific policies should be defined, approved by management, published, communicated to and acknowledged by relevant personnel and relevant interested parties, and reviewed at planned intervals and if significant changes occur.', 'organizational', 'preventive', 'A.5'],
            ['A.5.2', 'Information security roles and responsibilities', 'Information security roles and responsibilities should be defined and allocated according to the organization needs.', 'organizational', 'preventive', 'A.5'],
            ['A.5.3', 'Segregation of duties', 'Conflicting duties and conflicting areas of responsibility should be segregated.', 'organizational', 'preventive', 'A.5'],
            ['A.5.4', 'Management responsibilities', 'Management should require all personnel to apply information security in accordance with the established information security policy, topic-specific policies and procedures of the organization.', 'organizational', 'preventive', 'A.5'],
            ['A.5.5', 'Contact with authorities', 'The organization should establish and maintain contact with relevant authorities.', 'organizational', 'preventive', 'A.5'],
            ['A.5.6', 'Contact with special interest groups', 'The organization should establish and maintain contact with special interest groups or other specialist security forums and professional associations.', 'organizational', 'preventive', 'A.5'],
            ['A.5.7', 'Threat intelligence', 'Information relating to information security threats should be collected and analysed to produce threat intelligence.', 'organizational', 'detective', 'A.5'],
            ['A.5.8', 'Information security in project management', 'Information security should be integrated into project management.', 'organizational', 'preventive', 'A.5'],
            ['A.5.9', 'Inventory of information and other associated assets', 'An inventory of information and other associated assets, including owners, should be developed and maintained.', 'organizational', 'preventive', 'A.5'],
            ['A.5.10', 'Acceptable use of information and other associated assets', 'Rules for the acceptable use and procedures for handling information and other associated assets should be identified, documented and implemented.', 'organizational', 'preventive', 'A.5'],
            ['A.5.11', 'Return of assets', 'Personnel and other interested parties as appropriate should return all the organizations assets in their possession upon change or termination of their employment, contract or agreement.', 'organizational', 'preventive', 'A.5'],
            ['A.5.12', 'Classification of information', 'Information should be classified based on the information security needs of the organization based on confidentiality, integrity, availability and relevant interested party requirements.', 'organizational', 'preventive', 'A.5'],
            ['A.5.13', 'Labelling of information', 'An appropriate set of procedures for information labelling should be developed and implemented in accordance with the information classification scheme adopted by the organization.', 'organizational', 'preventive', 'A.5'],
            ['A.5.14', 'Information transfer', 'Information transfer rules, procedures, or agreements should be in place for all types of transfer facilities within the organization and between the organization and other parties.', 'organizational', 'preventive', 'A.5'],
            ['A.5.15', 'Access control', 'Rules to control physical and logical access to information and other associated assets should be established and implemented based on business and information security requirements.', 'organizational', 'preventive', 'A.5'],
            ['A.5.16', 'Identity management', 'The full life cycle of identities should be managed.', 'organizational', 'preventive', 'A.5'],
            ['A.5.17', 'Authentication information', 'Allocation and management of authentication information should be controlled by a management process, including advising personnel on appropriate handling of authentication information.', 'organizational', 'preventive', 'A.5'],
            ['A.5.18', 'Access rights', 'Access rights to information and other associated assets should be provisioned, reviewed, modified and removed in accordance with the organizations topic-specific policy on and rules for access control.', 'organizational', 'preventive', 'A.5'],
            ['A.5.19', 'Information security in supplier relationships', 'Processes and procedures should be defined and implemented to manage the information security risks associated with the use of suppliers products or services.', 'organizational', 'preventive', 'A.5'],
            ['A.5.20', 'Addressing information security within supplier agreements', 'Relevant information security requirements should be established and agreed with each supplier based on the type of supplier relationship.', 'organizational', 'preventive', 'A.5'],
            ['A.5.21', 'Managing information security in the ICT supply chain', 'Processes and procedures should be defined and implemented to manage the information security risks associated with the ICT products and services supply chain.', 'organizational', 'preventive', 'A.5'],
            ['A.5.22', 'Monitoring, review and change management of supplier services', 'The organization should regularly monitor, review, evaluate and manage change in supplier information security practices and service delivery.', 'organizational', 'detective', 'A.5'],
            ['A.5.23', 'Information security for use of cloud services', 'Processes for acquisition, use, management and exit from cloud services should be established in accordance with the organizations information security requirements.', 'organizational', 'preventive', 'A.5'],
            ['A.5.24', 'Information security incident management planning and preparation', 'The organization should plan and prepare for managing information security incidents by defining, establishing and communicating information security incident management processes, roles and responsibilities.', 'organizational', 'preventive', 'A.5'],
            ['A.5.25', 'Assessment and decision on information security events', 'The organization should assess information security events and decide if they are to be categorized as information security incidents.', 'organizational', 'detective', 'A.5'],
            ['A.5.26', 'Response to information security incidents', 'Information security incidents should be responded to in accordance with the documented procedures.', 'organizational', 'corrective', 'A.5'],
            ['A.5.27', 'Learning from information security incidents', 'Knowledge gained from information security incidents should be used to strengthen and improve the information security controls.', 'organizational', 'corrective', 'A.5'],
            ['A.5.28', 'Collection of evidence', 'The organization should establish and implement procedures for the identification, collection, acquisition and preservation of evidence related to information security events.', 'organizational', 'detective', 'A.5'],
            ['A.5.29', 'Information security during disruption', 'The organization should plan how to maintain information security at an appropriate level during disruption.', 'organizational', 'preventive', 'A.5'],
            ['A.5.30', 'ICT readiness for business continuity', 'ICT readiness should be planned, implemented, maintained and tested based on business continuity objectives and ICT continuity requirements.', 'technological', 'preventive', 'A.5'],
            ['A.5.31', 'Legal, statutory, regulatory and contractual requirements', 'Legal, statutory, regulatory and contractual requirements relevant to information security and the organizations approach to meet these requirements should be identified, documented and kept up to date.', 'organizational', 'preventive', 'A.5'],
            ['A.5.32', 'Intellectual property rights', 'The organization should implement appropriate procedures to protect intellectual property rights.', 'organizational', 'preventive', 'A.5'],
            ['A.5.33', 'Protection of records', 'Records should be protected from loss, destruction, falsification, unauthorized access and unauthorized release.', 'organizational', 'preventive', 'A.5'],
            ['A.5.34', 'Privacy and protection of PII', 'The organization should identify and meet the requirements regarding the preservation of privacy and protection of PII according to applicable laws and regulations and contractual requirements.', 'organizational', 'preventive', 'A.5'],
            ['A.5.35', 'Independent review of information security', 'The organizations approach to managing information security and its implementation including people, processes and technologies should be reviewed independently at planned intervals, or when significant changes occur.', 'organizational', 'detective', 'A.5'],
            ['A.5.36', 'Compliance with policies, rules and standards for information security', 'Compliance with the organizations information security policy, topic-specific policies, rules and standards should be regularly reviewed.', 'organizational', 'detective', 'A.5'],
            ['A.5.37', 'Documented operating procedures', 'Operating procedures for information processing facilities should be documented and made available to personnel who need them.', 'organizational', 'preventive', 'A.5'],

            // A.6 People Controls
            ['A.6.1', 'Screening', 'Background verification checks on all candidates to become personnel should be carried out prior to joining the organization and on an ongoing basis taking into consideration applicable laws, regulations, ethics and be proportional to the business requirements, the classification of the information to be accessed and the perceived risks.', 'people', 'preventive', 'A.6'],
            ['A.6.2', 'Terms and conditions of employment', 'The employment contractual agreements should state the personnel and the organizations responsibilities for information security.', 'people', 'preventive', 'A.6'],
            ['A.6.3', 'Information security awareness, education and training', 'Personnel of the organization and relevant interested parties should receive appropriate information security awareness, education and training and regular updates of the organizations information security policy, topic-specific policies and procedures, as relevant for their job function.', 'people', 'preventive', 'A.6'],
            ['A.6.4', 'Disciplinary process', 'A disciplinary process should be formalized and communicated to take actions against personnel and other relevant interested parties who have committed an information security policy violation.', 'people', 'corrective', 'A.6'],
            ['A.6.5', 'Responsibilities after termination or change of employment', 'Information security responsibilities and duties that remain valid after termination or change of employment should be defined, enforced and communicated to relevant personnel and other interested parties.', 'people', 'preventive', 'A.6'],
            ['A.6.6', 'Confidentiality or non-disclosure agreements', 'Confidentiality or non-disclosure agreements reflecting the organizations needs for the protection of information should be identified, documented, regularly reviewed and signed by personnel and other relevant interested parties.', 'people', 'preventive', 'A.6'],
            ['A.6.7', 'Remote working', 'Security measures should be implemented when personnel work remotely to protect information accessed, processed or stored outside the organizations premises.', 'people', 'preventive', 'A.6'],
            ['A.6.8', 'Information security event reporting', 'The organization should provide a mechanism for personnel to report observed or suspected information security events through appropriate channels in a timely manner.', 'people', 'detective', 'A.6'],

            // A.7 Physical Controls
            ['A.7.1', 'Physical security perimeters', 'Security perimeters should be defined and used to protect areas that contain information and other associated assets.', 'physical', 'preventive', 'A.7'],
            ['A.7.2', 'Physical entry', 'Secure areas should be protected by appropriate entry controls and access points.', 'physical', 'preventive', 'A.7'],
            ['A.7.3', 'Securing offices, rooms and facilities', 'Physical security for offices, rooms and facilities should be designed and implemented.', 'physical', 'preventive', 'A.7'],
            ['A.7.4', 'Physical security monitoring', 'Continuous monitoring of the physical security of the organizations premises and facilities should be implemented.', 'physical', 'detective', 'A.7'],
            ['A.7.5', 'Protecting against physical and environmental threats', 'Protection against physical and environmental threats, such as natural disasters and other intentional or unintentional physical threats to infrastructure should be designed and implemented.', 'physical', 'preventive', 'A.7'],
            ['A.7.6', 'Working in secure areas', 'Security measures for working in secure areas should be designed and implemented.', 'physical', 'preventive', 'A.7'],
            ['A.7.7', 'Clear desk and clear screen', 'Clear desk rules for papers and removable storage media and clear screen rules for information processing facilities should be defined and appropriately enforced.', 'physical', 'preventive', 'A.7'],
            ['A.7.8', 'Equipment siting and protection', 'Equipment should be sited securely and protected.', 'physical', 'preventive', 'A.7'],
            ['A.7.9', 'Security of assets off-premises', 'Off-site assets should be protected.', 'physical', 'preventive', 'A.7'],
            ['A.7.10', 'Storage media', 'Storage media should be managed through their life cycle of acquisition, use, transportation and disposal in accordance with the organizations classification scheme and handling requirements.', 'physical', 'preventive', 'A.7'],
            ['A.7.11', 'Supporting utilities', 'Information processing facilities should be protected from power failures and other disruptions caused by failures in supporting utilities.', 'physical', 'preventive', 'A.7'],
            ['A.7.12', 'Cabling security', 'Cables carrying power, data or supporting information services should be protected from interception, interference or damage.', 'physical', 'preventive', 'A.7'],
            ['A.7.13', 'Equipment maintenance', 'Equipment should be maintained correctly to ensure availability, integrity and confidentiality of information.', 'physical', 'corrective', 'A.7'],
            ['A.7.14', 'Secure disposal or re-use of equipment', 'Items of equipment containing storage media should be verified to ensure that any sensitive data and licensed software has been removed or securely overwritten prior to disposal or re-use.', 'physical', 'preventive', 'A.7'],

            // A.8 Technological Controls
            ['A.8.1', 'User endpoint devices', 'Information stored on, processed by or accessible via user endpoint devices should be protected.', 'technological', 'preventive', 'A.8'],
            ['A.8.2', 'Privileged access rights', 'The allocation and use of privileged access rights should be restricted and managed.', 'technological', 'preventive', 'A.8'],
            ['A.8.3', 'Information access restriction', 'Access to information and other associated assets should be restricted in accordance with the established topic-specific policy on access control.', 'technological', 'preventive', 'A.8'],
            ['A.8.4', 'Access to source code', 'Read and write access to source code, development tools and software libraries should be appropriately managed.', 'technological', 'preventive', 'A.8'],
            ['A.8.5', 'Secure authentication', 'Secure authentication technologies and procedures should be implemented based on information access restrictions and the topic-specific policy on access control.', 'technological', 'preventive', 'A.8'],
            ['A.8.6', 'Capacity management', 'The use of resources should be monitored, tuned and projections made of future capacity requirements to ensure the required system performance.', 'technological', 'preventive', 'A.8'],
            ['A.8.7', 'Protection against malware', 'Protection against malware should be implemented and supported by appropriate user awareness.', 'technological', 'preventive', 'A.8'],
            ['A.8.8', 'Management of technical vulnerabilities', 'Information about technical vulnerabilities of information systems in use should be obtained, the organizations exposure to such vulnerabilities evaluated and appropriate measures taken.', 'technological', 'preventive', 'A.8'],
            ['A.8.9', 'Configuration management', 'Configurations, including security configurations, of hardware, software, services and networks should be established, documented, implemented, monitored and reviewed.', 'technological', 'preventive', 'A.8'],
            ['A.8.10', 'Information deletion', 'Information stored in information systems, devices or in any other storage media should be deleted when no longer required.', 'technological', 'preventive', 'A.8'],
            ['A.8.11', 'Data masking', 'Data masking should be used in accordance with the organizations topic-specific policy on access control and other related topic-specific policies, and business requirements, taking applicable legislation into consideration.', 'technological', 'preventive', 'A.8'],
            ['A.8.12', 'Data leakage prevention', 'Data leakage prevention measures should be applied to systems, networks and any other devices that process, store or transmit sensitive information.', 'technological', 'preventive', 'A.8'],
            ['A.8.13', 'Information backup', 'Backup copies of information, software and systems should be maintained and regularly tested in accordance with the agreed topic-specific policy on backup.', 'technological', 'corrective', 'A.8'],
            ['A.8.14', 'Redundancy of information processing facilities', 'Information processing facilities should be implemented with redundancy sufficient to meet availability requirements.', 'technological', 'preventive', 'A.8'],
            ['A.8.15', 'Logging', 'Logs that record events, generate evidence, ensure integrity of log information and prevent against unauthorized access should be produced, stored, protected and analysed.', 'technological', 'detective', 'A.8'],
            ['A.8.16', 'Monitoring activities', 'Networks, systems and applications should be monitored for anomalous behaviour and appropriate actions taken to evaluate potential information security incidents.', 'technological', 'detective', 'A.8'],
            ['A.8.17', 'Clock synchronization', 'The clocks of all relevant information processing systems within an organization or security domain should be synchronized to approved time sources.', 'technological', 'preventive', 'A.8'],
            ['A.8.18', 'Use of privileged utility programs', 'The use of utility programs that might be capable of overriding system and application controls should be restricted and tightly controlled.', 'technological', 'preventive', 'A.8'],
            ['A.8.19', 'Installation of software on operational systems', 'Procedures and measures should be implemented to securely manage software installation on operational systems.', 'technological', 'preventive', 'A.8'],
            ['A.8.20', 'Networks security', 'Networks and network devices should be secured, managed and controlled to protect information in systems and applications.', 'technological', 'preventive', 'A.8'],
            ['A.8.21', 'Security of network services', 'Security mechanisms, service levels and service requirements of network services should be identified, implemented and monitored.', 'technological', 'preventive', 'A.8'],
            ['A.8.22', 'Segregation of networks', 'Groups of information services, users and information systems should be segregated in the organizations networks.', 'technological', 'preventive', 'A.8'],
            ['A.8.23', 'Web filtering', 'Access to external websites should be managed to reduce exposure to malicious content.', 'technological', 'preventive', 'A.8'],
            ['A.8.24', 'Use of cryptography', 'Rules for the effective use of cryptography, including cryptographic key management, should be defined and implemented.', 'technological', 'preventive', 'A.8'],
            ['A.8.25', 'Secure development life cycle', 'Rules for the secure development of software and systems should be established and applied.', 'technological', 'preventive', 'A.8'],
            ['A.8.26', 'Application security requirements', 'Information security requirements should be identified, specified and approved when developing or acquiring applications.', 'technological', 'preventive', 'A.8'],
            ['A.8.27', 'Secure system architecture and engineering principles', 'Principles for engineering secure systems should be established, documented, maintained and applied to any information system development activities.', 'technological', 'preventive', 'A.8'],
            ['A.8.28', 'Secure coding', 'Secure coding principles should be applied to software development.', 'technological', 'preventive', 'A.8'],
            ['A.8.29', 'Security testing in development and acceptance', 'Security testing processes should be defined and implemented in the development life cycle.', 'technological', 'detective', 'A.8'],
            ['A.8.30', 'Outsourced development', 'The organization should direct, monitor and review the activities related to outsourced system development.', 'technological', 'preventive', 'A.8'],
            ['A.8.31', 'Separation of development, test and production environments', 'Development, testing and production environments should be separated and secured.', 'technological', 'preventive', 'A.8'],
            ['A.8.32', 'Change management', 'Changes to information processing facilities and information systems should be subject to change management procedures.', 'technological', 'preventive', 'A.8'],
            ['A.8.33', 'Test information', 'Test information should be appropriately selected, protected and managed.', 'technological', 'preventive', 'A.8'],
            ['A.8.34', 'Protection of information systems during audit testing', 'Audit tests and other assurance activities involving assessment of operational systems should be planned and agreed between the tester and appropriate management.', 'technological', 'preventive', 'A.8'],
        ];

        $annexADomains = [
            'A.5' => Domain::where('code', 'A.5')->first(),
            'A.6' => Domain::where('code', 'A.6')->first(),
            'A.7' => Domain::where('code', 'A.7')->first(),
            'A.8' => Domain::where('code', 'A.8')->first(),
        ];

        foreach ($annexAControls as $control) {
            Control::create([
                'domain_id' => $annexADomains[$control[5]]->id,
                'control_id' => $control[0],
                'title' => $control[1],
                'description' => $control[2],
                'category' => $control[3],
                'control_type' => $control[4],
                'is_active' => true,
            ]);
        }
    }
}