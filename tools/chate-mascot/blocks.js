(function (blocks, editor, element, components, apiFetch) {
    var el = element.createElement;
    var SelectControl = components.SelectControl;
    var useState = element.useState;
    var useEffect = element.useEffect; // Correctly access useEffect
    
    blocks.registerBlockType('sensorica/sensorica-chat', {
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

            // Fetch chats and update options
            useEffect(function () {
                apiFetch({ path: '?rest_route=/sensorica/v1/chats/' }).then(function (chats) {
                    var newOptions = chats.map(function (chat) {
                        return { value: chat.id.toString(), label: chat.title };
                    });
                    setOptions([{ value: '', label: 'Select a Chat' }, ...newOptions]);
                });
            }, []); // Empty dependency array means this runs once when the component mounts

            function onChangeSelectChat(newChat) {
                setAttributes({ selectedChat: newChat });
            }

            function getEditUrl(postId) {
                return `${wp.data.select("core/editor").getEditorSettings().editPostURLRoot}post.php?post=${postId}&action=edit`;
            }

            // Function to render the "Edit Prompt" link
            function renderEditLink() {
                if (selectedChat && selectedChat !== '') {
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
                renderEditLink() // Render the edit link (if applicable)
            );
        },
        save: function (props) {
            return el('div', null, '[sensorica_chat id="' + props.attributes.selectedChat + '"]');
        }
    });
}(window.wp.blocks, window.wp.editor, window.wp.element, window.wp.components, window.wp.apiFetch));
