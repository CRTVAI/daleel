<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\Course;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('user_video_progress')->truncate();
        DB::table('contents')->truncate();
        DB::table('courses')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create the standard courses
        $courses = [
            'AI & ML' => Course::create([
                'title' => 'AI & Machine Learning Foundations',
                'description' => 'A comprehensive introduction to machine learning, neural networks, LLMs, and prompt engineering.',
                'category' => 'AI & ML',
                'status' => 'active',
                'thumbnail' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=800&q=80',
            ]),
            'Web Development' => Course::create([
                'title' => 'Full-Stack Web Development Bootcamp',
                'description' => 'Master modern frontend and backend development with HTML, CSS, JavaScript, React, Node.js, and APIs.',
                'category' => 'Web Development',
                'status' => 'active',
                'thumbnail' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=800&q=80',
            ]),
            'Data Science' => Course::create([
                'title' => 'Data Science & Analytical Engineering',
                'description' => 'Learn SQL, Python, Pandas, Tableau, and Machine Learning models to analyze and visualize complex datasets.',
                'category' => 'Data Science',
                'status' => 'active',
                'thumbnail' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&q=80',
            ]),
            'Design' => Course::create([
                'title' => 'Modern UI/UX Design & Typography',
                'description' => 'Explore professional design systems, prototyping, wireframing, color theory, and UX research methods.',
                'category' => 'Design',
                'status' => 'active',
                'thumbnail' => 'https://images.unsplash.com/photo-1586717791821-3f44a563eb4c?w=800&q=80',
            ]),
            'Productivity' => Course::create([
                'title' => 'Ultrafast Productivity Masterclass',
                'description' => 'Unlock maximum remote work efficiency using Notion, Obsidian, Zapier automations, and ChatGPT.',
                'category' => 'Productivity',
                'status' => 'active',
                'thumbnail' => 'https://images.unsplash.com/photo-1506784983877-45594efa4cbe?w=800&q=80',
            ]),
            'Marketing' => Course::create([
                'title' => 'High-Conversion Digital Marketing',
                'description' => 'Learn SEO, social media growth, email marketing, Google Ads, and Facebook Ads strategies.',
                'category' => 'Marketing',
                'status' => 'active',
                'thumbnail' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&q=80',
            ]),
        ];

        // Keep track of how many lessons we've added to each course
        $courseCounts = [
            'AI & ML' => 0,
            'Web Development' => 0,
            'Data Science' => 0,
            'Design' => 0,
            'Productivity' => 0,
            'Marketing' => 0,
        ];

        $videos = [
            // ── AI & ML ──────────────────────────────────────────────
            ['AI & ML','Beginner','What is Artificial Intelligence?','kCc8FmEb1nY',3600,'AI basics, machine learning, deep learning, neural networks'],
            ['AI & ML','Beginner','Neural Networks from Scratch','aircAruvnKk',1800,'neural network, backpropagation, deep learning'],
            ['AI & ML','Beginner','Machine Learning Crash Course','7eh4d6sabA0',5400,'supervised learning, regression, classification, google'],
            ['AI & ML','Beginner','AI Tools Every Beginner Should Know','hfIUstzHs9A',1200,'AI tools, chatgpt, midjourney, productivity'],
            ['AI & ML','Beginner','ChatGPT Prompt Engineering for Beginners','T9aRN47eCD0',2700,'prompt engineering, chatgpt, openai, LLM', ['chatgpt']],
            ['AI & ML','Beginner','Introduction to Large Language Models','zjkBMFhNj_g',3300,'LLM, transformer, BERT, GPT, language model'],
            ['AI & ML','Beginner','How Stable Diffusion Works','sFztPP9qPRc',1500,'stable diffusion, generative AI, image generation'],
            ['AI & ML','Beginner','Getting Started with Google Gemini','jBFF3KLbOkc',1200,'gemini, google AI, multimodal AI'],
            ['AI & ML','Beginner','10 Best Free AI Tools in 2024','Pj0neYUp9Tk',1800,'free AI tools, productivity, students'],
            ['AI & ML','Beginner','How Does ChatGPT Actually Work?','PaCmpygFfXo',1500,'chatgpt, openai, transformer, nlp', ['chatgpt', 'openai']],
            ['AI & ML','Intermediate','Build a GPT from Scratch','kCc8FmEb1nY',7200,'GPT, pytorch, transformer, karpathy'],
            ['AI & ML','Intermediate','DeepSeek Coder Tutorial','pZifonagAPo',3600,'deepseek, coding, llm', ['deepseek', 'deepseek coder']],
            ['AI & ML','Intermediate','Hugging Face Transformers Full Course','00GKzGyWFEs',7200,'hugging face, NLP, transformers, BERT'],
            ['AI & ML','Intermediate','Build AI Agents with LangChain','MX6UmWiB_P0',5400,'langchain, agents, LLM, python'],
            ['AI & ML','Intermediate','RAG Systems Explained','T-D1OfcDW1M',2700,'RAG, retrieval augmented generation, vector database'],
            ['AI & ML','Intermediate','PyTorch Deep Learning Full Course','GIsg-ZUy0MY',14400,'pytorch, deep learning, CNN, training'],
            ['AI & ML','Intermediate','AI Image Generation with ComfyUI','ZB5ByVIKWuY',3600,'comfyui, stable diffusion, workflow, nodes'],
            ['AI & ML','Intermediate','Vector Databases Explained','klTvEwg3oJ4',1800,'vector database, embeddings, pinecone, faiss'],
            ['AI & ML','Intermediate','Build a Chatbot with Python','9hE5-98ZeCg',3600,'chatbot, python, nlp, dialogue systems'],
            ['AI & ML','Advanced','Attention Is All You Need – Paper Explained','iDulhoQ2pro',3600,'transformer, attention, self-attention, paper review'],
            ['AI & ML','Advanced','Building LLMs from Scratch','UU1WVnFnCvo',10800,'LLM, training, tokenizer, pytorch'],
            ['AI & ML','Advanced','Reinforcement Learning from Human Feedback','2MBJOuVq380',2700,'RLHF, reward model, PPO, alignment'],
            ['AI & ML','Advanced','Diffusion Models Explained','fbLgFerlkkk',2700,'diffusion, DDPM, stable diffusion, math'],

            // ── Web Development ───────────────────────────────────────
            ['Web Development','Beginner','HTML & CSS Full Course for Beginners','HGTJBPNC-os',21600,'html, css, web dev, beginner'],
            ['Web Development','Beginner','JavaScript Full Course for Beginners','PkZNo7MFNFg',21600,'javascript, js, beginner, functions, DOM'],
            ['Web Development','Beginner','CSS Flexbox in 15 Minutes','fYq5PXgSsbE',900,'flexbox, css layout, responsive'],
            ['Web Development','Beginner','CSS Grid Tutorial','EiNiSFIPIQE',1200,'css grid, layout, responsive design'],
            ['Web Development','Beginner','What is an API? (Explained for Beginners)','s7wmiS2mSXY',480,'api, REST, http, web services'],
            ['Web Development','Beginner','Git and GitHub for Beginners','RGOj5yH7evk',4200,'git, github, version control'],
            ['Web Development','Beginner','Learn Node.js - Full Tutorial','Oe421EPjeBE',21600,'nodejs, javascript, backend, express'],
            ['Web Development','Beginner','React for Beginners – Full Course','w7ejDZ8SWv8',7200,'react, jsx, components, hooks'],
            ['Web Development','Beginner','Build Your First Website in 1 Hour','UB1O30fR-EE',3600,'html, css, first website'],
            ['Web Development','Beginner','Responsive Web Design Full Course','srvUrASNj0s',7200,'responsive, media queries, mobile first'],
            ['Web Development','Intermediate','Next.js 14 Full Course','ZVnjOPwW6ZA',14400,'nextjs, react, ssr, app router'],
            ['Web Development','Intermediate','Vue.js 3 Full Course','YrxBx-a4mBY',14400,'vuejs, vue 3, composition api'],
            ['Web Development','Intermediate','TypeScript Full Course','30LWjhZzeSo',10800,'typescript, types, interfaces, generics'],
            ['Web Development','Intermediate','REST API with Node.js & Express','fgTGADljAeg',5400,'REST, API, nodejs, express, mongodb'],
            ['Web Development','Intermediate','GraphQL Full Course','ed8SykqL1HA',9000,'graphql, api, schema, queries'],
            ['Web Development','Intermediate','Docker for Web Developers','3c-iBn73dDE',5400,'docker, containers, devops, deployment'],
            ['Web Development','Intermediate','Deploy Full Stack App to AWS','Jnpxou-KOrk',7200,'aws, deployment, ec2, s3, devops'],
            ['Web Development','Intermediate','Laravel 10 Full Course','ImGBRRwBKq8',14400,'laravel, php, backend, eloquent'],
            ['Web Development','Advanced','Microservices with Node.js','5jXMniFlzmc',21600,'microservices, nodejs, scalability'],
            ['Web Development','Advanced','System Design for Beginners','m8Icp_Cid5o',3600,'system design, scalability, architecture'],
            ['Web Development','Advanced','WebSockets Real-Time App','1BfCnjr_Vjg',5400,'websockets, real-time, nodejs, socket.io'],

            // ── Data Science ──────────────────────────────────────────
            ['Data Science','Beginner','Data Science Full Course for Beginners','ua-CiDNNj30',21600,'data science, python, pandas, numpy'],
            ['Data Science','Beginner','Python for Data Science','LHBE0fqKMKg',5400,'python, data science, pandas, matplotlib'],
            ['Data Science','Beginner','What is Data Science?','X3paOmcrTjQ',1800,'data science, career, intro'],
            ['Data Science','Beginner','SQL for Data Analysis Full Course','HXV3zeQKqGY',14400,'sql, database, queries, data analysis'],
            ['Data Science','Beginner','Google Sheets for Data Analysis','qTNwSHSqCFc',7200,'google sheets, data analysis, pivot tables', ['google sheets', 'sheets']],
            ['Data Science','Beginner','Statistics for Data Science','xxpc-HPKV9M',5400,'statistics, probability, mean, standard deviation'],
            ['Data Science','Intermediate','Pandas Full Tutorial','vmEHCKfzCMY',7200,'pandas, dataframe, python, data manipulation'],
            ['Data Science','Intermediate','Matplotlib & Seaborn Visualization','3Xc3CA655Y4',5400,'matplotlib, seaborn, visualization, charts'],
            ['Data Science','Intermediate','Machine Learning with scikit-learn','pqNCD_5r0IU',7200,'scikit-learn, machine learning, python'],
            ['Data Science','Intermediate','Data Cleaning in Python','UGDnTp2ifio',3600,'data cleaning, pandas, missing values'],
            ['Data Science','Intermediate','Power BI Full Course','AGrl-H87pRU',10800,'power bi, dashboard, visualization, microsoft'],
            ['Data Science','Intermediate','Tableau Tutorial for Beginners','jEgVto5QME8',5400,'tableau, visualization, dashboard, business intelligence'],
            ['Data Science','Advanced','Deep Learning for Tabular Data','RM5qefasAEs',3600,'deep learning, tabular, neural network'],
            ['Data Science','Advanced','Time Series Forecasting','_ZQ-lQrK9Rg',5400,'time series, forecasting, ARIMA, python'],

            // ── Design ────────────────────────────────────────────────
            ['Design','Beginner','Figma for Beginners – Full Course','Cx2dkpBxst8',5400,'figma, ui design, wireframe, prototyping'],
            ['Design','Beginner','UI/UX Design Fundamentals','c9Wg6Cb3ULU',3600,'ui design, ux design, principles'],
            ['Design','Beginner','Color Theory for Designers','AvgCkHrcj8w',1800,'color theory, palette, design principles'],
            ['Design','Beginner','Typography Basics Every Designer Needs','sByzHoiYFX0',1200,'typography, fonts, design, layout'],
            ['Design','Beginner','Canva Tutorial for Beginners','pjMqUFPBDlY',3600,'canva, graphic design, social media'],
            ['Design','Beginner','How to Design a Logo','hAGPKPiPP8k',2400,'logo design, brand identity, illustrator'],
            ['Design','Beginner','Design Principles Every Developer Should Know','yNDgFK2Jj1E',1800,'design principles, ui, visual hierarchy'],
            ['Design','Intermediate','Figma Advanced Features','YmdtKC_WKEI',3600,'figma, components, auto layout, design system'],
            ['Design','Intermediate','Build a Design System in Figma','EK-pHkc5EL4',5400,'design system, figma, tokens, components'],
            ['Design','Intermediate','UX Research Methods','3HJMHrmF3ZM',2700,'ux research, user testing, interviews'],
            ['Design','Intermediate','Motion Design with After Effects','GFO_txvwKZc',5400,'after effects, motion design, animation'],
            ['Design','Advanced','Designing for Accessibility','Rf85jIEiQH8',2700,'accessibility, a11y, wcag, inclusive design'],
            ['Design','Advanced','Advanced Prototyping in Figma','EK-pHkc5EL4',3600,'figma, advanced prototyping, interaction design'],

            // ── Productivity ──────────────────────────────────────────
            ['Productivity','Beginner','Notion for Beginners – Complete Guide','oTahLEX3NXo',5400,'notion, productivity, organization, notes', ['notion']],
            ['Productivity','Beginner','How to Use Obsidian for Note Taking','DbsAQSIKgrY',3600,'obsidian, note taking, PKM, knowledge management'],
            ['Productivity','Beginner','Pomodoro Technique Explained','mNBmG24djoY',900,'pomodoro, time management, focus, study'],
            ['Productivity','Beginner','Build a Second Brain with Notion','K0ta8BaFFn8',5400,'second brain, notion, productivity, PKM', ['notion']],
            ['Productivity','Beginner','Google Workspace for Students','3KTnf89mKCY',3600,'google workspace, gmail, docs, drive'],
            ['Productivity','Beginner','Time Blocking – Most Productive Strategy','w4B6HdAFHbI',1200,'time blocking, schedule, productivity'],
            ['Productivity','Beginner','Morning Routine for Maximum Productivity','sLNDIMbkuDs',1800,'morning routine, productivity, habits'],
            ['Productivity','Intermediate','Automate Tasks with Zapier','Vb8kHqzxuKc',3600,'zapier, automation, no-code, workflow'],
            ['Productivity','Intermediate','ChatGPT for Productivity','pLX6CLLhJBQ',2700,'chatgpt, productivity, ai tools, workflows', ['chatgpt']],
            ['Productivity','Intermediate','Slack Basics for Remote Teams','YmufQ09c22I',5400,'slack, remote work, communication', ['slack']],
            ['Productivity','Intermediate','n8n Automation Tutorial','1MwSoB0gnM4',7200,'n8n, automation, workflow, self-hosted'],
            ['Productivity','Intermediate','Deep Work – Study with Me','UJ-X4EkRMwA',14400,'deep work, focus, study, concentration'],

            // ── Business ──────────────────────────────────────────────
            ['Business','Beginner','How to Start a Business in 2024','NnoMK9eBWOg',2700,'startup, entrepreneurship, business model'],
            ['Business','Beginner','Business Model Canvas Explained','IP0cUBWTgpY',1800,'business model canvas, startup, strategy'],
            ['Business','Beginner','Personal Finance for Students','HQzoZfc3GwQ',3600,'personal finance, budgeting, investing, students'],
            ['Business','Beginner','Freelancing 101 – How to Get Clients','rLwqjJfQKn4',3600,'freelancing, clients, portfolio, upwork'],
            ['Business','Beginner','LinkedIn Profile Tips for Beginners','YJF04YFkFkU',1800,'linkedin, profile, networking, job search', ['linkedin']],
            ['Business','Intermediate','How to Build a SaaS in 2024','HOUfM_yw_9M',5400,'saas, startup, product, software business'],
            ['Business','Intermediate','Venture Capital Explained','LJUBLVqh_ME',2400,'venture capital, funding, startup, investors'],
            ['Business','Intermediate','The Lean Startup Methodology','tIlEogtAMoA',2700,'lean startup, MVP, pivot, product market fit'],
            ['Business','Intermediate','Product Management Fundamentals','GHbZnCjAITI',3600,'product management, roadmap, user stories, agile'],
            ['Business','Advanced','How to Raise Funding for Your Startup','4RHxRHXInZo',3600,'fundraising, pitch deck, investors, seed round'],
            ['Business','Advanced','Scaling a Tech Company to $1M ARR','lHvMCjQXe4c',3600,'scaling, ARR, growth, revenue'],

            // ── Marketing ─────────────────────────────────────────────
            ['Marketing','Beginner','Digital Marketing for Beginners','hiXmFDh-fZA',5400,'digital marketing, strategy, beginner, online'],
            ['Marketing','Beginner','SEO Full Course 2024','68zvMPmEiaE',7200,'seo, search engine optimization, google, keywords'],
            ['Marketing','Beginner','Content Marketing Strategy','aKPNt5W-L48',2700,'content marketing, blogging, strategy'],
            ['Marketing','Beginner','Email Marketing for Beginners','qOZqUqbFaHE',3600,'email marketing, mailchimp, campaigns'],
            ['Marketing','Beginner','Instagram Growth Strategy','8uLBe5TZxU8',2700,'instagram, social media, growth hacking', ['instagram']],
            ['Marketing','Intermediate','Google Ads Tutorial 2024','X8oBPDGVDVw',7200,'google ads, ppc, paid traffic, campaign', ['google ads']],
            ['Marketing','Intermediate','Facebook Ads Full Course','Eu5j0QD9e6M',7200,'facebook ads, meta, paid social, targeting', ['facebook']],
            ['Marketing','Intermediate','YouTube SEO – Rank Your Videos','wU3BVeTxzBw',2700,'youtube seo, rank, algorithm, thumbnails', ['youtube']],
            ['Marketing','Intermediate','Analytics with Google Analytics 4','_H7gPe0m7IE',5400,'google analytics, GA4, tracking, data'],
            ['Marketing','Advanced','Growth Hacking Strategies','p3JIRzK5HrU',3600,'growth hacking, funnel, acquisition, retention'],

            // ── Cybersecurity ─────────────────────────────────────────
            ['Cybersecurity','Beginner','Cybersecurity for Beginners','3Kq1MIfTWCE',5400,'cybersecurity, hacking, security basics'],
            ['Cybersecurity','Beginner','How HTTPS Works','T4Df5_cojAs',900,'https, ssl, tls, encryption, security'],
            ['Cybersecurity','Beginner','Common Cyber Attacks Explained','Dk-ZqQ-bfy4',1800,'cyber attacks, phishing, malware, social engineering'],
            ['Cybersecurity','Beginner','Password Security – Do It Right','aEmossALAxI',1200,'passwords, password manager, security'],
            ['Cybersecurity','Beginner','Linux for Ethical Hacking – Beginner','UV4lfq_sWa4',7200,'linux, kali, ethical hacking, terminal'],
            ['Cybersecurity','Intermediate','Ethical Hacking Full Course','lZAoFs75_cs',21600,'ethical hacking, penetration testing, kali linux'],
            ['Cybersecurity','Intermediate','Network Security Basics','E03gh1zRRVY',3600,'network security, firewalls, protocols'],
            ['Cybersecurity','Intermediate','Web Application Hacking','rJmCMblgGnk',7200,'web hacking, owasp, sql injection, xss'],
            ['Cybersecurity','Intermediate','Wireshark Tutorial','qTaOZrDnMkQ',3600,'wireshark, network analysis, packet capture'],
            ['Cybersecurity','Advanced','Advanced Penetration Testing','2_lswM1S264',10800,'penetration testing, exploit, privilege escalation'],
            ['Cybersecurity','Advanced','Bug Bounty Hunting – Full Guide','NtzZZKZAm0k',7200,'bug bounty, responsible disclosure, hacking'],

            // ── Extra AI & ML top-ups ─────────────────────────────────
            ['AI & ML','Beginner','Midjourney v6 Complete Guide','XxvjLlW99J4',3600,'midjourney, ai art, image generation, prompts'],
            ['AI & ML','Beginner','DALL-E 3 Full Guide','4C_3EtRWbgk',2700,'dall-e, openai, image generation, ai art'],
            ['AI & ML','Beginner','AI for Students – Boost Your Study','zIkBqjOwnFI',2700,'ai, students, study, chatgpt, productivity'],
            ['AI & ML','Intermediate','OpenAI API Python Tutorial','czvVibB2lRA',5400,'openai api, python, gpt, integration'],
            ['AI & ML','Intermediate','Local LLMs with Ollama','Wjrdr0NU4Sk',3600,'ollama, local llm, llama, privacy'],
            ['AI & ML','Intermediate','AI Automation with Make.com','4OvpNkOiXOg',3600,'make.com, automation, no-code, AI'],
            ['AI & ML','Advanced','Multi-Agent AI Systems','qqG0qDo-g0I',5400,'multi-agent, AI agents, crewai, autogen'],
            ['AI & ML','Advanced','LLM Security & Prompt Injection','Sv-sNQrHBSQ',2700,'LLM security, prompt injection, AI safety'],

            // ── Extra Web Dev top-ups ──────────────────────────────────
            ['Web Development','Beginner','Python Full Course for Beginners','_uQrJ0TkZlc',18000,'python, beginner, programming, functions'],
            ['Web Development','Beginner','Bootstrap 5 Crash Course','4sosXZsdy-s',5400,'bootstrap, css framework, responsive'],
            ['Web Development','Intermediate','Tailwind CSS Full Course','pfaSUYaSgpY',7200,'tailwindcss, utility classes, responsive'],
            ['Web Development','Intermediate','Prisma ORM with Node.js','RebA5J-rlwg',5400,'prisma, orm, database, nodejs'],
            ['Web Development','Intermediate','Redis Crash Course','jgpVdJB2sKQ',2700,'redis, caching, performance, database'],
            ['Web Development','Advanced','Kubernetes for Beginners','X48VuDVv0do',14400,'kubernetes, k8s, containers, orchestration'],
            ['Web Development','Advanced','CI/CD Pipeline with GitHub Actions','R8_veQiYBjI',3600,'github actions, cicd, automation, devops'],

            // ── Extra Data Science top-ups ────────────────────────────
            ['Data Science','Beginner','Excel Pivot Tables Tutorial','g530cnFfk8Y',3600,'excel, pivot tables, data analysis'],
            ['Data Science','Intermediate','Python Web Scraping Full Course','gyZrpBHaESk',7200,'web scraping, beautiful soup, scrapy, python'],
            ['Data Science','Advanced','MLOps Full Course','Jl5JbTzUGVo',10800,'mlops, model deployment, pipeline, mlflow'],
            ['Data Science','Advanced','Feature Engineering for ML','3_SzmEGdMbs',5400,'feature engineering, machine learning, preprocessing'],

            // ── Extra Design top-ups ──────────────────────────────────
            ['Design','Beginner','Adobe XD Tutorial for Beginners','3aOU9MbITLM',5400,'adobe xd, ui design, wireframing'],
            ['Design','Intermediate','Spline 3D Design for Beginners','dLFyaQzQbHs',3600,'spline, 3d design, web 3d, interactive'],
            ['Design','Intermediate','Lottie Animations for Web','vrRnkSmrDyM',2700,'lottie, animation, web design, after effects'],

            // ── Extra Productivity top-ups ────────────────────────────
            ['Productivity','Beginner','Best Study Techniques – Evidence Based','CPxSzqKPJQ4',2700,'study techniques, learning, spaced repetition'],
            ['Productivity','Intermediate','PKM with Logseq','b6Stzf9VSRI',5400,'logseq, pkm, note taking, knowledge graph'],
            ['Productivity','Advanced','Building Your Own Automation System','3N9E3uC0Q3g',7200,'automation, no-code, workflow, productivity'],

            // ── Extra Business ────────────────────────────────────────
            ['Business','Beginner','How to Write a Business Plan','Fqch5OrUPgg',2700,'business plan, startup, entrepreneur'],
            ['Business','Intermediate','Negotiation Skills for Professionals','MbtKuLMGHDY',3600,'negotiation, salary, communication, business'],
        ];

        $now = now();
        $inserts = [];

        foreach ($videos as $v) {

            [
                $category,
                $skill_level,
                $title,
                $youtubeInput,
                $duration,
                $tags
            ] = $v;

            $connected_tools = isset($v[6]) ? json_encode($v[6]) : json_encode([]);

            /*
            |--------------------------------------------------------------------------
            | Extract YouTube ID
            |--------------------------------------------------------------------------
            */

            $yt_id = null;

            // Direct ID
            if (preg_match('/^[A-Za-z0-9_-]{11}$/', $youtubeInput)) {

                $yt_id = $youtubeInput;

            } else {

                // Try extracting from URL
                preg_match(
                    '/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([A-Za-z0-9_-]{11})/',
                    $youtubeInput,
                    $matches
                );

                $yt_id = $matches[1] ?? null;
            }

            // Invalid YouTube ID
            if (!$yt_id) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Verify Video Exists
            |--------------------------------------------------------------------------
            */

            try {

                $response = Http::timeout(10)->get(
                    "https://www.youtube.com/oembed",
                    [
                        'url'    => "https://www.youtube.com/watch?v={$yt_id}",
                        'format' => 'json',
                    ]
                );

                // Skip deleted/private/broken videos
                if (!$response->successful()) {
                    continue;
                }

                $videoData = $response->json();

            } catch (\Exception $e) {

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Course & Module Mapping
            |--------------------------------------------------------------------------
            */
            $course_id = null;
            $section_part_label = null;
            $sort_order = 0;

            if (isset($courses[$category]) && $courseCounts[$category] < 8) {
                $course_id = $courses[$category]->id;
                $courseCounts[$category]++;
                $sort_order = $courseCounts[$category];

                if ($sort_order <= 3) {
                    $section_part_label = 'Module 1: Getting Started';
                } elseif ($sort_order <= 6) {
                    $section_part_label = 'Module 2: Practical Core Skills';
                } else {
                    $section_part_label = 'Module 3: Advanced Applications';
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Insert Data
            |--------------------------------------------------------------------------
            */

            $inserts[] = [
                'title'              => $videoData['title'] ?? $title,
                'video_url'          => "https://www.youtube.com/watch?v={$yt_id}",
                'youtube_id'         => $yt_id,
                'duration_seconds'   => (int) $duration,
                'thumbnail'          => "https://img.youtube.com/vi/{$yt_id}/hqdefault.jpg",
                'description'        => "A professional {$skill_level}-level {$category} course covering: {$tags}.",
                'tags'               => $tags,
                'category'           => $category,
                'skill_level'        => $skill_level,
                'status'             => 'active',
                'connected_tools'    => $connected_tools,
                'slug'               => Str::slug($videoData['title'] ?? $title) . '-' . Str::random(5),
                'course_id'          => $course_id,
                'section_part_label' => $section_part_label,
                'sort_order'         => $sort_order,
                'created_at'         => $now,
                'updated_at'         => $now,
            ];

            // Periodic insert for resilience and progress
            if (count($inserts) >= 250) {
                DB::table('contents')->insert($inserts);
                $inserts = [];
            }
        }

        // Final insert for remaining records
        if (count($inserts) > 0) {
            DB::table('contents')->insert($inserts);
        }
    }
}
