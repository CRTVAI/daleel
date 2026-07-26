<?php

namespace Database\Seeders;

use App\Models\AiPrompt;
use Illuminate\Database\Seeder;

class AiPromptSeeder extends Seeder
{
    public function run(): void
    {
        $prompts = [
            [
                'key' => 'search_parser_system',
                'title' => 'Advanced Search: System Prompt',
                'description' => 'Processes user queries on advanced search page.',
                'prompt' => "You are an intelligent search query processor for a video learning platform.\nYour job is to understand the user's intent and return a structured JSON response.",
            ],
            [
                'key' => 'search_parser_user',
                'title' => 'Advanced Search: Parsing Prompt',
                'description' => 'Extracts query types and tools.',
                'prompt' => "USER QUERY: \"{raw_query}\"\n\nAVAILABLE TOOLS IN OUR PLATFORM:\n{tool_list_str}\n\nYOUR TASKS:\n1. Fix any spelling or typo mistakes in the query.\n2. Classify the request into ONE type:\n   - \"single_video\": User wants ONE specific tutorial/how-to video (e.g. \"how to connect excel\", \"fix css error\", \"what is an API\").\n   - \"course\": User wants to deeply learn a tool or topic (e.g. \"Excel Masterclass\", \"learn Python\", \"how to master Laravel\").\n   - \"roadmap\": User has a broad career/productivity goal (e.g. \"become a pro\", \"grow my career\", \"learning path for automation\").\n3. Extract ONLY tool names from the AVAILABLE TOOLS list that the user mentioned or clearly implied.\n4. Summarize the user's goal/intent in one short sentence.\n5. Give a confidence score (0.0–1.0) for your classification.\n\nCRITICAL RULES:\n- \"How to learn [Tool]\" or \"How to master [Tool]\" → always \"course\".\n- Broad career/productivity goals (not tool-specific) → always \"roadmap\".\n- Specific how-to tasks → \"single_video\".\n- Only extract tools from the AVAILABLE TOOLS list. If no tool matches, return an empty array.\n\nRespond ONLY with valid JSON in this exact format (no markdown, no explanation):\n{\n  \"fixed_query\": \"corrected query text\",\n  \"query_type\": \"single_video|course|roadmap\",\n  \"tools_mentioned\": [\"ToolA\", \"ToolB\"],\n  \"goals_intent\": \"short description of what the user wants\",\n  \"confidence_score\": 0.95\n}",
            ],
            [
                'key' => 'search_tool_matching',
                'title' => 'Advanced Search: Tool Matching',
                'description' => 'Matches user goals to tools for roadmap creation.',
                'prompt' => "GOAL: {goal}\nTOOLS:\n{tool_list}\nSelect only the tools that are directly mentioned or absolutely essential to achieving the goal. Avoid selecting tangentially related tools (e.g. do not select Python for an Excel goal unless Python is explicitly mentioned or requested). Reply ONLY with the comma-separated IDs.",
            ],
            [
                'key' => 'search_hybrid_pick',
                'title' => 'Advanced Search: Hybrid Pick',
                'description' => 'Selects the single best match ID from a course/video list.',
                'prompt' => "User is searching for a {type_label}: \"{query}\"\n\nAvailable {type_label}s:\n{list}\n\nTASK: Find the single best matching ID.\n\nRULES:\n- Match by meaning, not just exact words (e.g., \"AI\" matches \"Artificial Intelligence\")\n- Handle common typos (e.g., \"excell\" matches \"Excel\", \"javscript\" matches \"JavaScript\")\n- Consider topic relevance and content scope\n- If nothing is a reasonable match, respond with 0\n- Reply ONLY with the ID number\n\nBest match ID:",
            ],
            [
                'key' => 'ai_mentor_system',
                'title' => 'AI Mentor: System Context Selector',
                'description' => 'Selects the relevant lesson IDs based on context/query.',
                'prompt' => "You are an AI Mentor. The user is in this context: '{search_context}'. Based on the query, pick the most relevant IDs. Return JSON: {\"ids\": []}.",
            ],
            [
                'key' => 'ai_mentor_user',
                'title' => 'AI Mentor: User Prompt',
                'description' => 'Sends list of candidate lessons to score.',
                'prompt' => "User Question: {query}\n\nCandidate Lessons:\n{lesson_context}",
            ],
        [
                'key' => 'inspector_plan_generator',
                'title' => 'Inspector Report: Teaching Plan Generator',
                'description' => 'Generates a structured teaching plan from inspector inputs and top tools.',
                'prompt' => "You are a training curriculum designer. Based on the context below, create a structured teaching guidance plan.\n\nTraining mode: {online_offline}\nTotal sessions: {total_session}\nHours per session: {hr_per_session}\nTime between sessions: {time_between_session}\nInstructor notes: {notes}\n\nTop tools used by learners (ONLY these tools are available — do NOT invent any tools):\n{tools_list}\n\nCRITICAL: You MUST ONLY use tools from the list above. Do NOT mention any other tools, platforms, or software. Every item's ToolName must appear in the list.\n\nReturn JSON with key 'sessions' — an array of session objects. Each session has:\n- session_number (int)\n- title (string, session focus)\n- items (array of strings, each formatted exactly as '* ToolName - what to teach'. The tool name must appear first, then a dash, then the skill/action. Example: '* ChatGPT - how to write professional emails' or '* Excel - how to build pivot tables' or '* Google Sheets - data validation techniques')",
            ],
        ];

        foreach ($prompts as $p) {
            AiPrompt::updateOrCreate(['key' => $p['key']], $p);
        }
    }
}
