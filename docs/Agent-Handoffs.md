# Agent Handoff Protocol

Agent handoffs transfer task context sequentially or conditionally:
`Orchestrator -> Research -> Website Architect -> Design -> Elementor -> Content -> SEO -> QA`

LoopProtector guards against infinite handoff loops (max 10 handoffs per execution chain).
