import React, { useState, useRef, useEffect } from 'react';

const ChatBot = () => {
  const [messages, setMessages] = useState([]);
  const [inputMessage, setInputMessage] = useState('');
  const [isLoading, setIsLoading] = useState(false);
  const [isOpen, setIsOpen] = useState(false);
  const messagesEndRef = useRef(null);

  // Auto-scroll to bottom when messages change
  const scrollToBottom = () => {
    messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' });
  };

  useEffect(() => {
    scrollToBottom();
  }, [messages]);

  const handleFeedback = (index, feedback) => {
    setMessages((prev) =>
      prev.map((msg, i) => (i === index ? { ...msg, feedback } : msg))
    );
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!inputMessage.trim()) return;

    setMessages((prev) => [
      ...prev,
      {
        text: inputMessage,
        isBot: false,
        feedback: null,
      },
    ]);

    setInputMessage('');
    setIsLoading(true);

    try {
      const response = await fetch('http://127.0.0.1:5000/ask_question', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ question: inputMessage }),
      });

      if (!response.ok) throw new Error('API request failed');
      const data = await response.json();
      setMessages((prev) => [
        ...prev,
        {
          text: data.answer,
          isBot: true,
          feedback: null,
        },
      ]);
    } catch (error) {
      setMessages((prev) => [
        ...prev,
        {
          text: 'Sorry, there was an error processing your request.',
          isBot: true,
          feedback: null,
        },
      ]);
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <div
      style={{
        position: 'fixed',
        bottom: '20px',
        right: '20px',
        zIndex: 1000,
      }}
    >
      <button
        style={{
          background: '#213362',
          border: 'none',
          borderRadius: '50%',
          width: '60px',
          height: '60px',
          cursor: 'pointer',
          boxShadow: '0 4px 12px rgba(0,0,0,0.2)',
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          transition: 'all 0.2s',
        }}
        onClick={() => setIsOpen(!isOpen)}
      >
        <span style={{ color: '#ffffff', fontSize: '24px' }}>💬</span>
      </button>

      {isOpen && (
        <div
          style={{
            width: '350px',
            height: '500px',
            background: '#ffffff',
            borderRadius: '15px',
            boxShadow: '0 8px 24px rgba(0,0,0,0.15)',
            display: 'flex',
            flexDirection: 'column',
            position: 'absolute',
            bottom: '80px',
            right: '0',
          }}
        >
          <div
            style={{
              background: '#213362',
              color: '#ffffff',
              padding: '16px',
              borderRadius: '15px 15px 0 0',
              display: 'flex',
              justifyContent: 'space-between',
              alignItems: 'center',
            }}
          >
            <h3 style={{ margin: '0', fontSize: '18px', color: '#ffffff' }}>
              🤖 Chat Assistant
            </h3>
            <button
              style={{
                background: 'none',
                border: 'none',
                color: '#ffffff',
                fontSize: '24px',
                cursor: 'pointer',
                padding: '0 8px',
              }}
              onClick={() => setIsOpen(false)}
            >
              ×
            </button>
          </div>

          <div
            style={{
              flex: 1,
              overflowY: 'auto',
              padding: '16px',
              background: '#f8f9fa',
            }}
          >
            {messages.map((message, index) => (
              <div
                key={index}
                style={{
                  margin: '8px 0',
                  display: 'flex',
                  justifyContent: message.isBot ? 'flex-start' : 'flex-end',
                }}
              >
                <div
                  style={{
                    display: 'flex',
                    alignItems: 'center',
                    gap: '8px',
                    flexDirection: message.isBot ? 'row' : 'row-reverse',
                  }}
                >
                  <span style={{ fontSize: '20px' }}>
                    {message.isBot ? '🤖' : '👤'}
                  </span>
                  <div
                    style={{
                      maxWidth: '80%',
                      padding: '12px 16px',
                      borderRadius: message.isBot
                        ? '20px 20px 20px 4px'
                        : '20px 20px 4px 20px',
                      background: message.isBot
                        ? 'rgba(33, 51, 98, 0.1)'
                        : '#213362',
                      color: message.isBot ? '#213362' : '#ffffff',
                      lineHeight: 1.4,
                      fontSize: '14px',
                    }}
                  >
                    {message.text}

                    {message.isBot && (
                      <div
                        style={{
                          display: 'flex',
                          gap: '8px',
                          marginTop: '8px',
                          justifyContent: 'flex-end',
                        }}
                      >
                        <button
                          onClick={() => handleFeedback(index, 'liked')}
                          style={{
                            background: 'none',
                            border: 'none',
                            cursor: 'pointer',
                            padding: '4px',
                            color:
                              message.feedback === 'liked'
                                ? '#f00340'
                                : '#cccccc',
                            transition: 'color 0.2s',
                          }}
                        >
                          <svg width='20' height='20' viewBox='0 0 24 24'>
                            <path
                              fill='currentColor'
                              d='M23 10a2 2 0 0 0-2-2h-6.32l.96-4.57c.02-.1.03-.21.03-.32c0-.41-.17-.79-.44-1.06L14.17 1L7.59 7.58C7.22 7.95 7 8.45 7 9v10a2 2 0 0 0 2 2h9c.83 0 1.54-.5 1.84-1.22l3.02-7.05c.09-.23.14-.47.14-.73V10zM1 21h4V9H1v12z'
                            />
                          </svg>
                        </button>

                        <button
                          onClick={() => handleFeedback(index, 'disliked')}
                          style={{
                            background: 'none',
                            border: 'none',
                            cursor: 'pointer',
                            padding: '4px',
                            color:
                              message.feedback === 'disliked'
                                ? '#f00340'
                                : '#cccccc',
                            transition: 'color 0.2s',
                          }}
                        >
                          <svg width='20' height='20' viewBox='0 0 24 24'>
                            <path
                              fill='currentColor'
                              d='M19 15h4V3h-4v12zm-8.29-8.7c-.28-.4-.71-.7-1.21-.7H2c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h7.5c.4 0 .78-.16 1.06-.44l5.5-5.5c.28-.28.44-.66.44-1.06V9c0-.55-.45-1-1-1h-2.21c-.4 0-.78-.16-1.06-.44l-1.29-1.3z'
                            />
                          </svg>
                        </button>
                      </div>
                    )}
                  </div>
                </div>
              </div>
            ))}

            {isLoading && (
              <div
                style={{
                  margin: '8px 0',
                  display: 'flex',
                  justifyContent: 'flex-start',
                }}
              >
                <div
                  style={{
                    display: 'inline-flex',
                    padding: '12px 16px',
                    gap: '6px',
                    background: 'rgba(33, 51, 98, 0.1)',
                    borderRadius: '20px 20px 20px 4px',
                  }}
                >
                  <span
                    style={{
                      width: '8px',
                      height: '8px',
                      background: '#f00340',
                      borderRadius: '50%',
                      animation: 'typing 1s infinite ease-in-out',
                    }}
                  ></span>
                  <span
                    style={{
                      width: '8px',
                      height: '8px',
                      background: '#f00340',
                      borderRadius: '50%',
                      animation: 'typing 1s infinite ease-in-out',
                      animationDelay: '0.2s',
                    }}
                  ></span>
                  <span
                    style={{
                      width: '8px',
                      height: '8px',
                      background: '#f00340',
                      borderRadius: '50%',
                      animation: 'typing 1s infinite ease-in-out',
                      animationDelay: '0.4s',
                    }}
                  ></span>
                </div>
              </div>
            )}
            <div ref={messagesEndRef} />
          </div>

          <form
            onSubmit={handleSubmit}
            style={{
              display: 'flex',
              gap: '8px',
              padding: '16px',
              background: '#ffffff',
              borderTop: '1px solid rgba(33, 51, 98, 0.1)',
            }}
          >
            <input
              type='text'
              value={inputMessage}
              onChange={(e) => setInputMessage(e.target.value)}
              placeholder='Type your message...'
              disabled={isLoading}
              style={{
                flex: 1,
                padding: '10px 16px',
                border: '1px solid rgba(33, 51, 98, 0.2)',
                borderRadius: '25px',
                fontSize: '14px',
                outline: 'none',
              }}
            />
            <button
              type='submit'
              disabled={isLoading}
              style={{
                background: '#f00340',
                color: '#ffffff',
                border: 'none',
                borderRadius: '25px',
                padding: '10px 20px',
                cursor: 'pointer',
                transition: 'background 0.2s',
                fontSize: '14px',
              }}
            >
              Send
            </button>
          </form>
        </div>
      )}

      <style jsx global>{`
        @keyframes typing {
          0%,
          100% {
            transform: translateY(0);
          }
          50% {
            transform: translateY(-4px);
          }
        }
      `}</style>
    </div>
  );
};

export default ChatBot;
