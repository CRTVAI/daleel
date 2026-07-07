<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate previous entries to verify clean 50 posts addition
        BlogPost::truncate();

        $topics = [
            [
                'title' => 'Scaling Workforce Productivity with AI Workflows',
                'category' => 'Insights',
                'excerpt' => 'An in-depth framework for integrating personalized AI mentors with daily enterprise routines, tracking active duration, and streamlining recurring processes.',
                'tags' => 'AI, productivity, workflow, technology',
                'author' => 'Dr. Elizabeth Vance',
                'minutes' => 6,
            ],
            [
                'title' => 'The Complete Department Prompt Library Setup Guide',
                'category' => 'Guides',
                'excerpt' => 'Step-by-step strategies for developing shared department context blocks, role-tailored rules, and structured system instructions to avoid custom output error variations.',
                'tags' => 'Prompt Engineering, Guides, Business, Learning',
                'author' => 'Marcus Thorne',
                'minutes' => 8,
            ],
            [
                'title' => 'Designing Frictionless Enterprise AI Adoption Portals',
                'category' => 'Tutorials',
                'excerpt' => 'A guide for UX designers and product managers on building interest-based trial signups, customized dashboards, and context-dependent sidebar curriculum players.',
                'tags' => 'UX Design, Product, AI Portals, Tutorials',
                'author' => 'Sonia K. Patel',
                'minutes' => 5,
            ],
            [
                'title' => 'Integrating Custom GPTs into Slack Workspace Channels',
                'category' => 'Tutorials',
                'excerpt' => 'How to setup direct integrations with external tools, routing user verification codes, and querying semantic indexes within team messaging systems.',
                'tags' => 'Slack, Integration, Automation, AI Integrations',
                'author' => 'David Miller',
                'minutes' => 7,
            ],
            [
                'title' => 'Maximizing Time Saved via Smart Extension Metrics',
                'category' => 'Insights',
                'excerpt' => 'A data-driven analysis of tracking user interaction times, minimizing page-transition delays, and mapping key productivity gains over 30-day rolls.',
                'tags' => 'Metrics, Data, Extension, Workplace Analytics',
                'author' => 'Jane R. Sterling',
                'minutes' => 9,
            ],
            [
                'title' => 'How to Safeguard Sensitive Corporate Data with Local LLMs',
                'category' => 'Security',
                'excerpt' => 'A detailed tutorial on running open-source code models locally, establishing secure API proxy nodes, and verifying compliance boundaries.',
                'tags' => 'Security, Local LLMs, Compliance, Data Privacy',
                'author' => 'Aaron Kowalski',
                'minutes' => 11,
            ],
            [
                'title' => 'Building Custom Onboarding Roadmaps for Engineering Teams',
                'category' => 'Guides',
                'excerpt' => 'Designing specialized role learning roadmaps that highlight technical tool stacks, test automation frameworks, and architectural design patterns.',
                'tags' => 'Engineering, Onboarding, Roadmap, Team Training',
                'author' => 'Sarah Lin',
                'minutes' => 10,
            ],
            [
                'title' => 'Unlocking the Power of RAG for Internal Wiki Search',
                'category' => 'Insights',
                'excerpt' => 'Retrieval Augmented Generation models improve internal search indexes. Learn to connect database schemas directly to semantic vector models.',
                'tags' => 'RAG, Vector Search, Wiki, AI Architectures',
                'author' => 'Nikhil Nair',
                'minutes' => 6,
            ],
            [
                'title' => 'A Practical Approach to API Rate Limit Mitigation',
                'category' => 'Developer',
                'excerpt' => 'Strategies for caching high-frequency external tool requests, utilizing Redis job queues, and formatting client response fallbacks.',
                'tags' => 'API, Caching, Redis, Scaling, PHP',
                'author' => 'Alexandre Dubois',
                'minutes' => 5,
            ],
            [
                'title' => 'Creating Multi-Agent Systems for Competitive Intelligence',
                'category' => 'Insights',
                'excerpt' => 'Deploying autonomous web-scraping agents that monitor external changes, compile reports, and deliver customized system alerts to admin dashboards.',
                'tags' => 'AI Agents, Scraping, Competitors, Marketing Trends',
                'author' => 'Chloe Bennett',
                'minutes' => 12,
            ],
            [
                'title' => 'Optimizing Vector Embeddings for Product Search Engines',
                'category' => 'Developer',
                'excerpt' => 'Technical walkthrough detailing cosine similarity index configuration, metadata tagging parameters, and embedding dimension reduction.',
                'tags' => 'Developer, Mathematics, Search, Vectors',
                'author' => 'Dr. Elizabeth Vance',
                'minutes' => 9,
            ],
            [
                'title' => '10 Time-Saving Prompt Templates for HR Teams',
                'category' => 'Guides',
                'excerpt' => 'A collection of copy-paste HR prompt models that simplify candidate screening reports, internal newsletters, policy guides, and interview plans.',
                'tags' => 'HR, Copy-Paste, Templates, Prompt Libraries',
                'author' => 'Marcus Thorne',
                'minutes' => 4,
            ],
            [
                'title' => 'The Role of AI Mentorship in Reducing Employee Churn',
                'category' => 'Insights',
                'excerpt' => 'How continuous personal learning roadmaps paired with on-demand skill gap analysis tools improve workplace loyalty and employee retention metric lines.',
                'tags' => 'Retention, HR, Training, Mentorship',
                'author' => 'Jane R. Sterling',
                'minutes' => 7,
            ],
            [
                'title' => 'Automating Design Hand-offs with Customized AI Checklists',
                'category' => 'Guides',
                'excerpt' => 'Reducing communication barriers between UI designers and front-end developer teams by leveraging standardized prompt validation scripts.',
                'tags' => 'UI UX, Figma, Collaboration, Workflows',
                'author' => 'Sonia K. Patel',
                'minutes' => 6,
            ],
            [
                'title' => 'Introduction to Laravel API Integration for AI Nodes',
                'category' => 'Developer',
                'excerpt' => 'Learn to construct dynamic Guzzle client requests, map JSON files, parse authorization headers, and log integration statuses cleanly.',
                'tags' => 'Laravel, PHP, Guzzle, REST API',
                'author' => 'Alexandre Dubois',
                'minutes' => 8,
            ],
            [
                'title' => 'How to Deploy Private LLM Instances on AWS EC2 Clusters',
                'category' => 'Security',
                'excerpt' => 'Setup guide for provisioning GPU-enabled servers, deploying Docker stacks, configurations for security groups, and routing custom domains.',
                'tags' => 'AWS, GPU, Security, Docker, Cloud Configurations',
                'author' => 'Aaron Kowalski',
                'minutes' => 14,
            ],
            [
                'title' => 'Enhancing Customer Inquiry Classifications with Few-Shot Prompts',
                'category' => 'Guides',
                'excerpt' => 'Improving the accuracy score of support ticket classification engines without spending thousands on dedicated model fine-tuning processes.',
                'tags' => 'Customer Support, Optimization, Prompt Tuning',
                'author' => 'David Miller',
                'minutes' => 5,
            ],
            [
                'title' => 'The Evolution of AI-Assisted Project Management Offices',
                'category' => 'Insights',
                'excerpt' => 'How modern PMOs leverage predictive algorithms to define milestones, flag bottleneck logs early, and optimize resource distributions.',
                'tags' => 'PMO, Strategy, Management, Project Planning',
                'author' => 'Sonia K. Patel',
                'minutes' => 8,
            ],
            [
                'title' => 'Fine-Tuning Code Llama for Proprietary Code Bases',
                'category' => 'Developer',
                'excerpt' => 'Step-by-step training pipeline: collecting code snippets, running validation loss audits, and creating developer completion plugins.',
                'tags' => 'Code Llama, Developer, Python, Machine Learning',
                'author' => 'Sarah Lin',
                'minutes' => 13,
            ],
            [
                'title' => 'Maximizing Browser Extension Efficiency with Manifest V3',
                'category' => 'Tutorials',
                'excerpt' => 'Upgrade checklist and debugging practices for shifting to service workers, declarative net requests, and session states.',
                'tags' => 'Chrome Extension, Javascript, Manifest V3, Web Dev',
                'author' => 'David Miller',
                'minutes' => 9,
            ],
            [
                'title' => 'Securing API Communication Key exchanges in Edge Platforms',
                'category' => 'Security',
                'excerpt' => 'Best practices for storing credentials in serverless functions, auditing JWT security layers, and setting automatic rotation intervals.',
                'tags' => 'Security, JWT, Serverless, Infrastructure',
                'author' => 'Aaron Kowalski',
                'minutes' => 10,
            ],
            [
                'title' => '5 Common Pitfalls in Team AI Readiness Strategies',
                'category' => 'Insights',
                'excerpt' => 'Why many corporate AI rollouts experience pushback and how to resolve adoption friction using specialized custom gamification incentives.',
                'tags' => 'Strategy, Change Management, Corporate Culture',
                'author' => 'Dr. Elizabeth Vance',
                'minutes' => 7,
            ],
            [
                'title' => 'Building Custom Markdown to HTML Converters in PHP',
                'category' => 'Developer',
                'excerpt' => 'Create lightweight, parse-regex systems to clean content structures, render blocks, and output SEO-friendly semantic tags.',
                'tags' => 'PHP, Markdown, Regex, HTML parser',
                'author' => 'Alexandre Dubois',
                'minutes' => 6,
            ],
            [
                'title' => 'A Designer Guide to Curating Cohesive Dashboard Color Palettes',
                'category' => 'Guides',
                'excerpt' => 'A guide to HSL color spacing calculations, contrast evaluations, accessibility levels, and implementing dark mode variables.',
                'tags' => 'UI UX, CSS, Dark Mode, Typography',
                'author' => 'Sonia K. Patel',
                'minutes' => 8,
            ],
            [
                'title' => 'The Future of Semantic Web Operations and XML Feed Parsing',
                'category' => 'Insights',
                'excerpt' => 'How semantic analysis tools translate old XML schemas into dynamic JSON models for immediate query generation.',
                'tags' => 'Data, Parsing, Feeds, XML JSON',
                'author' => 'Sarah Lin',
                'minutes' => 9,
            ],
            [
                'title' => 'Integrating OAuth2 Login Flows in Corporate Learning Portals',
                'category' => 'Security',
                'excerpt' => 'Enabling seamless single sign-on (SSO) with Okta and Google Workspaces, and populating dashboard group mappings dynamically.',
                'tags' => 'SSO, Okta, Authentication, Security',
                'author' => 'Aaron Kowalski',
                'minutes' => 11,
            ],
            [
                'title' => 'Creating Dynamic Video Overlays with YouTube IFrame API',
                'category' => 'Tutorials',
                'excerpt' => 'Implementing resume-from-progress checkpoints, overlay next triggers, and sidebar watch integrations for custom video platforms.',
                'tags' => 'Javascript, HTML5, Video Player, YouTube API',
                'author' => 'David Miller',
                'minutes' => 6,
            ],
            [
                'title' => 'Scaling Database Indexes for High-Frequency Event Storage',
                'category' => 'Developer',
                'excerpt' => 'Database architectural guide: setting composite keys, optimizing auto-increment properties, and tracking usage stats logs.',
                'tags' => 'Database, MySQL, Indexing, Performance',
                'author' => 'Nikhil Nair',
                'minutes' => 10,
            ],
            [
                'title' => 'Deploying Departmental Knowledge Bases using Notion APIs',
                'category' => 'Tutorials',
                'excerpt' => 'Connect company wiki frameworks directly to custom prompt queries to keep internal operations models accurate.',
                'tags' => 'Notion, Knowledge Base, API Integration',
                'author' => 'Marcus Thorne',
                'minutes' => 5,
            ],
            [
                'title' => 'Conducting Ethical AI Audits in Corporate Environments',
                'category' => 'Security',
                'excerpt' => 'Setting audit parameters, verifying models clean of private records, and reporting audit statuses back to operations managers.',
                'tags' => 'Compliance, Security, Ethics, Audit Frameworks',
                'author' => 'Dr. Elizabeth Vance',
                'minutes' => 9,
            ],
            [
                'title' => 'A Complete Guide to CSS Grid and Flexbox Layout Systems',
                'category' => 'Guides',
                'excerpt' => 'Master complex card grid alignments, alignment shifts on small devices, responsive parameters, and performance audits.',
                'tags' => 'CSS, Frontend, Grid system, Web Design',
                'author' => 'Sonia K. Patel',
                'minutes' => 7,
            ],
            [
                'title' => 'Analyzing Workplace Productivity using User Activity Timelines',
                'category' => 'Insights',
                'excerpt' => 'How aggregating browser active durations helps developers highlight tool adoption and build tailored roadmap upgrades.',
                'tags' => 'Workplace, Productivity, Analytics, User Logs',
                'author' => 'Jane R. Sterling',
                'minutes' => 8,
            ],
            [
                'title' => 'Getting Started with Automated Testing inside Laravel Projects',
                'category' => 'Developer',
                'excerpt' => 'A developer guide for setting SQLite in-memory databases, running PHPUnit commands, and auditing API JSON structures.',
                'tags' => 'PHPUnit, Laravel, Testing, TDD',
                'author' => 'Alexandre Dubois',
                'minutes' => 9,
            ],
            [
                'title' => 'Best Practices for Writing High-Quality Meta Description Tags',
                'category' => 'Guides',
                'excerpt' => 'Optimize target keywords, match target search phrases, write short calls-to-action, and improve organic click-through ratios.',
                'tags' => 'SEO, Marketing, Text Copy, Search Engines',
                'author' => 'Marcus Thorne',
                'minutes' => 4,
            ],
            [
                'title' => 'Deploying Chatbots on Kubernetes Clusters using Helm Deployments',
                'category' => 'Developer',
                'excerpt' => 'Establishing scalable replica systems, configuring ingress rules, managing secret environments, and scaling nodes values.',
                'tags' => 'Kubernetes, Helm, DevOps, Kubernetes Scaling',
                'author' => 'Sarah Lin',
                'minutes' => 12,
            ],
            [
                'title' => 'Building Custom Chrome Extensions to Automate Data Extraction',
                'category' => 'Tutorials',
                'excerpt' => 'Write script inject injections, aggregate active table records, compile CSV logs, and dispatch items to webhook nodes.',
                'tags' => 'Chrome Extension, Automation, Javascript',
                'author' => 'David Miller',
                'minutes' => 8,
            ],
            [
                'title' => 'Developing Pro Trial Workflows that conversion metrics support',
                'category' => 'Insights',
                'excerpt' => 'Implementing premium modal highlights, vertical summary tables, and seamless access upgrades on active databases.',
                'tags' => 'UX Design, SaaS conversions, Billing Models',
                'author' => 'Jane R. Sterling',
                'minutes' => 6,
            ],
            [
                'title' => 'Why WebVTT Subtitle Conversions improve student Engagement',
                'category' => 'Guides',
                'excerpt' => 'Converting raw text catalogs into WebVTT logs enables screen reader support, semantic indexing, and multi-language learning.',
                'tags' => 'WebVTT, Education, Interactive Video',
                'author' => 'Sonia K. Patel',
                'minutes' => 5,
            ],
            [
                'title' => 'Optimizing Database Queries with Laravel Debugbar Tools',
                'category' => 'Developer',
                'excerpt' => 'Eliminating duplicate database loading errors, tracking query durations, and auditing caching effectiveness.',
                'tags' => 'Laravel, Debugging, SQL, Performance Tuning',
                'author' => 'Alexandre Dubois',
                'minutes' => 7,
            ],
            [
                'title' => 'Creating Interactive Course Roadmaps with Drag and Drop Canvas',
                'category' => 'Tutorials',
                'excerpt' => 'Building web editor dashboards using HTML5 drag events, mapping connection paths, and updating records values via AJAX.',
                'tags' => 'Javascript, HTML5 Canvas, Interactive Design',
                'author' => 'Sonia K. Patel',
                'minutes' => 11,
            ],
            [
                'title' => 'Analyzing NLP Tokenization Algorithms for Multilingual Data',
                'category' => 'Insights',
                'excerpt' => 'A review of byte-pair tokens, sentence-piece tools, and parsing accuracy metrics on complex Unicode scripts.',
                'tags' => 'NLP, Tokenization, Data Science, AI Systems',
                'author' => 'Sarah Lin',
                'minutes' => 10,
            ],
            [
                'title' => 'How to Implement Feature Gates inside Enterprise CRM Portals',
                'category' => 'Security',
                'excerpt' => 'Managing access to restricted menus, setting manual permission keys, and designing premium trial gateways.',
                'tags' => 'Security, Role-Based Access, CRM, Security Gates',
                'author' => 'Aaron Kowalski',
                'minutes' => 8,
            ],
            [
                'title' => '10 Essential AI Extension Tools for Front-End Web Developers',
                'category' => 'Guides',
                'excerpt' => 'Improve coding speed with inline syntax completions, automated styling evaluations, and error telemetry plugins.',
                'tags' => 'Developer, Web Dev, Productivity, Extensions',
                'author' => 'David Miller',
                'minutes' => 5,
            ],
            [
                'title' => 'The Influence of Micro-Animations on Learning Portal Metrics',
                'category' => 'Insights',
                'excerpt' => 'A detailed study of hover transitions, scale-in modals, and active bar animations on user session lengths.',
                'tags' => 'UI UX, Micro-Animations, User Retention',
                'author' => 'Sonia K. Patel',
                'minutes' => 7,
            ],
            [
                'title' => 'Constructing Robust Database Migrations in Collaborative SaaS Teams',
                'category' => 'Developer',
                'excerpt' => 'Strategies for avoiding conflicting timestamps, rolling back indexes cleanly, and managing target file updates.',
                'tags' => 'Laravel, MySQL, SaaS architecture, Code Integrity',
                'author' => 'Alexandre Dubois',
                'minutes' => 6,
            ],
            [
                'title' => 'Implementing Live Search Auto-Completions inside Web Pages',
                'category' => 'Tutorials',
                'excerpt' => 'Leverage debounce triggers, fetch data via asynchronous JSON routes, and render interactive lists cards.',
                'tags' => 'Javascript, API fetching, AJAX, UX Design',
                'author' => 'David Miller',
                'minutes' => 7,
            ],
            [
                'title' => 'How Google Search Indexes Dynamic Single-Page Applications',
                'category' => 'Guides',
                'excerpt' => 'How search crawlers process JavaScript layouts and why server-rendered meta tags remain critical for rankings.',
                'tags' => 'SEO, Google Crawlers, SPA rendering, Marketing',
                'author' => 'Marcus Thorne',
                'minutes' => 9,
            ],
            [
                'title' => 'Fine-Tuning Customer Support Chatbots with Real Dialogues',
                'category' => 'Insights',
                'excerpt' => 'Auditing response accuracies, mapping key confidence levels, and avoiding circular loop conversation issues.',
                'tags' => 'Customer Support, Chatbots, ML tuning, AI Strategy',
                'author' => 'Jane R. Sterling',
                'minutes' => 8,
            ],
            [
                'title' => 'Managing Team Budgets for Generative AI API Operations',
                'category' => 'Guides',
                'excerpt' => 'Strategies for establishing hard cost limits, caching queries, and assigning keys quotas to developers.',
                'tags' => 'Billing, Management, API Cost, Business Operations',
                'author' => 'Dr. Elizabeth Vance',
                'minutes' => 6,
            ],
            [
                'title' => 'The Ultimate Roadmap to Becoming a Workplace AI Integrator',
                'category' => 'Guides',
                'excerpt' => 'A complete checklist of tool setups, prompt templates, analytics frameworks, and security methodologies to master.',
                'tags' => 'Career, AI Integrator, Certification, Learning Paths',
                'author' => 'Marcus Thorne',
                'minutes' => 12,
            ]
        ];

        // Seed 50 distinct blog posts with professional cover images, SEO elements and HTML formats
        $unsplashIds = [
            'photo-1516321318423-f06f85e504b3', 'photo-1526374965328-7f61d4dc18c5', 
            'photo-1451187580459-43490279c0fa', 'photo-1488590528505-98d2b5aba04b',
            'photo-1518770660439-4636190af475', 'photo-1531297484001-80022131f5a1', 
            'photo-1550751827-4bd374c3f58b', 'photo-1460925895917-afdab827c52f',
            'photo-1504868584819-f8e8b4b6d7e3', 'photo-1498050108023-c5249f4df085'
        ];

        foreach ($topics as $index => $t) {
            $imageId = $unsplashIds[$index % count($unsplashIds)];
            $coverImage = "https://images.unsplash.com/".$imageId."?w=800&auto=format&fit=crop&q=80";

            $slug = Str::slug($t['title']);
            
            // Build rich body content using the details
            $content = "<h2>Overview</h2>
<p>In modern high-efficiency operations, understanding <strong>" . e($t['title']) . "</strong> is crucial. Organizations that leverage standardized workflow rules consistently see rapid improvements in tasks execution speed.</p>
<blockquote>" . e($t['excerpt']) . "</blockquote>
<h2>Strategic Pillars of Implementation</h2>
<p>To implement this successfully, teams must follow structured milestones:</p>
<ol>
  <li>Perform initial workflow dependency logging to capture latency.</li>
  <li>Ensure team leads design role guidance instructions.</li>
  <li>Monitor active interaction duration via dashboard statistics tables.</li>
</ol>
<p>When custom tools are tagged accurately, user feedback loops can be aggregated continuously to ensure quality generation outputs.</p>
<h2>Best Practices & Takeaways</h2>
<p>Review the primary tool settings, verify authorization codes parameters, and compile regular performance summaries for admin review. Continuous learning prevents adoption setbacks.</p>";

            BlogPost::create([
                'title' => $t['title'],
                'slug' => $slug,
                'content' => $content,
                'excerpt' => $t['excerpt'],
                'cover_image' => $coverImage,
                'category' => $t['category'],
                'tags' => $t['tags'],
                'author_name' => $t['author'],
                'author_avatar' => null,
                'read_time_minutes' => $t['minutes'],
                'meta_title' => $t['title'] . ' | Daleel AI Resources',
                'meta_description' => $t['excerpt'],
                'meta_keywords' => str_replace(' ', ', ', $t['tags']),
                'status' => 'published',
                'is_featured' => ($index === 0), // First post is featured
                'published_at' => now()->subHours($index * 4), // Stagger published times
            ]);
        }
    }
}
