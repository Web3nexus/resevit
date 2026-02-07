# AI Chat & Social Connection Setup Guide

This guide explains how to connect business social media accounts and enable the AI assistant.

## 1. Social Media Connections
The platform supports WhatsApp, Facebook (Messenger), and Instagram.

### Prerequisites
- A Facebook Business Page.
- A WhatsApp Business Account (WABA).
- Instagram Professional account linked to your Facebook Page.

### Steps
1. Navigate to **Settings > Connections** in the Business Dashboard.
2. Click **Connect** for the desired platform.
3. Complete the OAuth flow to authorize Resevit.
4. Once authorized, the platform will automatically fetch your Page/Account details.

## 2. AI Assistant Configuration
The AI assistant can handle customer enquiries and bookings.

### Setup
1. Go to **Marketing > Chat Automation**.
2. Create a new flow with the trigger type **AI Assistant (Take charge of chat)**.
3. Ensure the flow is marked as **Active**.

### How it Works
- **Auto-Reply**: When a customer messages via WhatsApp/FB/IG, the AI assistant will respond.
- **Manual Takeover**: If a staff member sends a message via the Resevit App or Dashboard, the AI assistant will automatically stop for that conversation.
- **Booking Integration**: The AI use the `ReservationService` to check availability and create bookings directly in the system.

## 3. Developer Configuration
Ensure the following environment variables are set in the backend:
- `OPENAI_API_KEY`: For AI responses.
- `FACEBOOK_CLIENT_ID` / `FACEBOOK_CLIENT_SECRET`: For social connections.
