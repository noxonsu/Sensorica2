(function (blocks, editor, element, components, apiFetch) {
    var el = element.createElement;
    var SelectControl = components.SelectControl;
    var useEffect = element.useEffect;
    var useState = element.useState;

    blocks.registerBlockType('sensorica2/sensorica-chat', {
        title: 'Sensorica Chat',
        icon: 'universal-access-alt',
        category: 'common',
        attributes: {
            selectedChat: {
                type: 'string',
                default: ''
            }
        },
        edit: function (props) {
            var selectedChat = props.attributes.selectedChat;
            var setAttributes = props.setAttributes;
            var [options, setOptions] = useState([{ value: '', label: 'Select a Chat' }]);

            var currentPostId = wp.data.select('core/editor').getCurrentPostId();

            // Add the 'Context Chat' option with the current post ID
            useEffect(function () {
                apiFetch({ path: '?rest_route=/sensorica2/v1/chats/' }).then(function (chats) {
                    var newOptions = chats.map(function (chat) {
                        return { value: chat.id.toString(), label: chat.title };
                    });
                    setOptions([
                        { value: '', label: 'Select a Chat' },
                        { value: currentPostId.toString(), label: 'Context Chat' }, // Set value to current post ID
                        ...newOptions
                    ]);
                });
            }, [currentPostId]); // Dependency array to update if currentPostId changes

            // Function to render the "Edit Prompt" link
            function renderEditLink() {
                if (selectedChat && selectedChat !== '' && selectedChat !== currentPostId.toString()) {
                    return el(
                        'a',
                        {
                            href: getEditUrl(selectedChat),
                            target: '_blank',
                            style: { marginLeft: '10px' }
                        },
                        'Edit Prompt'
                    );
                }
                return null;
            }
            // Function to display a message for 'Context Chat'
            function renderContextChatMessage() {
                if (selectedChat === currentPostId.toString()) { // Check if 'Context Chat' is selected
                    return el(
                        'p',
                        {},
                        'System prompt loads the content of the current post, and the user will chat, ask questions, etc.'
                    );
                }
                return null;
            }
            function onChangeSelectChat(newChat) {
                setAttributes({ selectedChat: newChat });
            }

            function getEditUrl(postId) {
                return `${wp.data.select("core/editor").getEditorSettings().editPostURLRoot}post.php?post=${postId}&action=edit`;
            }



            return el(
                'div', { className: props.className },
                el(
                    SelectControl,
                    {
                        label: 'Select a Chat',
                        value: selectedChat,
                        options: options,
                        onChange: onChangeSelectChat
                    }
                ),
                renderEditLink(), // Render the edit link (if applicable)
                renderContextChatMessage() // Render the message for 'Context Chat'
            );
        },
        save: function (props) {
            return el('div', null, '[sensorica_chat id="' + props.attributes.selectedChat + '"]');
        }
    });
}(window.wp.blocks, window.wp.editor, window.wp.element, window.wp.components, window.wp.apiFetch));
