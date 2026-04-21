# **OnTheFly Plugin \- Context & Memory**

This document serves as the primary memory and context for the development of the **OnTheFly** WordPress plugin. It ensures that the project requirements and standards are preserved.

## **Project Identity**

* **Plugin Name:** OnTheFly  
* **Developed by:** InnoSoft (Mohamed Ahmed Ghanam \- CEO)  
* **Category:** Standalone WordPress Plugin

## **Core Philosophy & Technical Standards**

* **Clean Code:** Minimalist approach, no unnecessary comments.  
* **Indentation:** Exactly 2-space indentation.  
* **Namespace:** OnTheFly\\Core  
* **Independence:** No external backend (Django-less); connects directly to translation providers.

## **Technical Specifications**

* **Approach:** Server-side (PHP) using the\_content and the\_title filters.  
* **HTML Parsing:** Using DOMDocument to translate only text nodes while preserving HTML structure and attributes.  
* **Caching:** Using set\_transient to store translations and minimize API costs.  
* **SEO:** URL-based language switching for search engine indexability.